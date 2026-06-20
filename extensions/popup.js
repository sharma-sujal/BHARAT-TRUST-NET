document.addEventListener('DOMContentLoaded', function () {
    chrome.tabs.query({ active: true, currentWindow: true }, function (tabs) {
        let activeTab = tabs[0];
        if (!activeTab || !activeTab.url) return;

        try {
            let urlObj = new URL(activeTab.url);
            let domain = urlObj.hostname;

            document.getElementById('institution').innerText = domain;

            fetch(`http://localhost/bharat-trustnet/backend/check.php?domain=${domain}`)
                .then(response => response.json())
                .then(data => {
                    updateUI(data);
                })
                .catch(error => {
                    console.error("Backend Error:", error);
                    document.getElementById('status-text').innerText = "Connection Error";
                    document.getElementById('message').innerText = "Make sure XAMPP Apache is running.";
                });

        } catch (e) {
            document.getElementById('status-text').innerText = "Invalid URL";
        }
    });
});

function updateUI(data) {
    const card = document.getElementById('status-card');
    const statusText = document.getElementById('status-text');
    const institution = document.getElementById('institution');
    const score = document.getElementById('trust-score');
    const message = document.getElementById('message');

    const trustScore = parseInt(data.trust_score) || 0;
    score.innerText = trustScore + " / 100";
    message.innerText = data.message;

    // Remove any old color classes first
    card.className = "card"; 

    // Dynamic Traffic Light Logic
    if (trustScore >= 80) {
        card.classList.add("verified"); // Green styling
        statusText.innerText = "OFFICIALLY SECURE";
        institution.innerText = data.institution + ` [${data.category}]`;
    } else if (trustScore >= 50 && trustScore < 80) {
        card.classList.add("caution"); // Orange styling
        statusText.innerText = "PROCEED WITH CAUTION";
        institution.innerText = data.institution !== "Unknown / Unverified" ? data.institution : data.domain;
    } else {
        card.classList.add("unverified"); // Red styling
        statusText.innerText = "HIGH RISK / UNVERIFIED";
        institution.innerText = data.domain;
    }
}