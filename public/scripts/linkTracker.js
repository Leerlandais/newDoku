console.log("Link Tracker");
document.querySelectorAll('.track-link').forEach(link => {
    link.addEventListener('click', function (event) {
        const linkId = event.currentTarget.id;

        console.log(linkId);
        navigator.sendBeacon('?route=logLinkConnect', new URLSearchParams({ id: linkId }));
    });
});