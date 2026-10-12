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

        public function getAllLogs() : array
        {
            $query = $this->db->query("SELECT * FROM doku_connection_logs ORDER BY log_id DESC");
            $logs = [];
            while ($row = $query->fetch()) {
                $logs[] = new DokuConnectionLogsMapping($row);
            }
            return $logs;
        }

    }