<?php

    namespace model\Manager;
    use model\Abstract\AbstractManager;
    use model\Mapping\DokuConnectionLogsMapping;
    use model\Mapping\DokuUserMapping;

    class ConnectionManager extends AbstractManager
    {
        public function logoutUser() : void
        {
            $_SESSION = [];

            if (ini_get("session.use_cookies")) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                );
            }
            session_destroy();
        }

        public function loginUser(array $data) : false|DokuUserMapping
        {

            $stmt = $this->db->prepare("SELECT * FROM doku_users WHERE doku_username = :username");
            $stmt->execute(["username" => $data["user_name"]]);
            $user = $stmt->fetch();
            if (!$user) return false;
            $login = password_verify($data["user_pass"], $user["doku_password"]);
            if ($login) {
                $_SESSION["user_id"] = $user["doku_user_id"];
                $_SESSION["user_name"] = $user["doku_username"];
                $_SESSION["roles"] = $user["doku_user_roles"];
                unset($user["doku_password"], $user["doku_user_created"], $user["doku_ip"]);
                return new DokuUserMapping($user);
            }
            return false;
        }

        public function logConnection(string $ip, string $location) : void
        {
    if($ip == MAIN_IP) return;
            if(in_array($ip, SECONDARY_IPS)) $ip = "EH";
            if($ip == "81.245.185.156") $ip = "CF2M";
            $insertData = [
                "log_ip" => $ip,
                "log_page_visited" => $location,
                "log_user" => $_SESSION["user_id"] ?? null
            ];
            $this->insertAnything($insertData, "doku_connection_logs");


        }

        public function getAllLogs($ip = false) : array
        {
            $whereClause = $ip ? "WHERE log_ip = :ip" : "";
            $query = $this->db->prepare("SELECT * FROM doku_connection_logs $whereClause ORDER BY log_id DESC");
            if($ip) {
                $query->bindValue(":ip", $ip, \PDO::PARAM_STR);
            }

                $query->execute();
            $logs = [];
            while ($row = $query->fetch()) {
                $logs[] = new DokuConnectionLogsMapping($row);
            }

            return $logs;
        }

        public function checkLoginAttempt(string $ip) : bool
        {
            $stmt = $this->db->prepare("SELECT COUNT(*) AS failed_attempts
FROM doku_connection_logs
WHERE log_ip = :ip
  AND log_page_visited = 'FAILED'
  AND log_date >= NOW() - INTERVAL 15 MINUTE");
            $stmt->bindParam(":ip", $ip, \PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result["failed_attempts"] > 2;
        }

    }