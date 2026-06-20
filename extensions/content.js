(function () {
    const domain = window.location.hostname.replace(/^www\./, '').toLowerCase();

    if (!domain || window.location.protocol === 'chrome:' || window.location.protocol === 'brave:') return;

    fetch(`http://localhost/bharat-trustnet/backend/check.php?domain=${domain}`)
        .then(response => response.json())
        .then(data => {
            showAutoNotice(data);
        })
        .catch(err => console.error("Bharat TrustNet Auto-Check Error:", err));
})();

function showAutoNotice(data) {
    const notice = document.createElement('div');
    notice.id = 'trustnet-auto-popup';
    
    const trustScore = parseInt(data.trust_score) || 0;
    
    // Traffic Light Color Setup
    let primaryColor, badgeText, statusLabel;
    if (trustScore >= 80) {
        primaryColor = '#1e7e34'; // Green
        badgeText = '✓ BHARAT TRUSTNET SECURE';
        statusLabel = data.institution;
    } else if (trustScore >= 50) {
        primaryColor = '#d39e00'; // Orange
        badgeText = '⚠ CAUTION: SUSPICIOUS ACTIVITY';
        statusLabel = data.institution !== "Unknown / Unverified" ? data.institution : data.domain;
    } else {
        primaryColor = '#bd2130'; // Red
        badgeText = '🚨 DANGER: UNVERIFIED SITE';
        statusLabel = data.domain;
    }
    
    notice.style.cssText = `
        position: fixed;
        top: 20px;
        right: -350px;
        width: 320px;
        background: #ffffff;
        border-left: 6px solid ${primaryColor};
        box-shadow: 0px 4px 15px rgba(0,0,0,0.2);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        padding: 15px;
        z-index: 2147483647;
        border-radius: 4px;
        transition: right 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    `;

    notice.innerHTML = `
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px;">
            <span style="font-weight: bold; font-size: 11px; letter-spacing: 0.5px; color: ${primaryColor};">
                ${badgeText}
            </span>
            <button id="trustnet-close-btn" style="background: none; border: none; font-size: 16px; cursor: pointer; color: #999; padding: 0 5px; line-height: 1;">&times;</button>
        </div>
        <div style="font-size: 14px; font-weight: bold; color: #222; margin-bottom: 4px;">
            ${statusLabel}
        </div>
        <div style="font-size: 12px; color: #666; margin-bottom: 10px;">
            Trust Score: <strong style="color: ${primaryColor};">${trustScore}/100</strong>
        </div>
        <div style="font-size: 11px; font-style: italic; color: #555; background: #f8f9fa; padding: 6px; border-radius: 3px;">
            ${data.message}
        </div>
    `;

    document.body.appendChild(notice);

    setTimeout(() => {
        notice.style.right = '20px';
    }, 400);

    notice.querySelector('#trustnet-close-btn').addEventListener('click', () => {
        notice.style.right = '-350px';
        setTimeout(() => notice.remove(), 500);
    });

    // Auto-dismiss secure green tags after 5 seconds, keep warnings open!
    if (trustScore >= 80) {
        setTimeout(() => {
            if (document.getElementById('trustnet-auto-popup')) {
                notice.style.right = '-350px';
                setTimeout(() => notice.remove(), 500);
            }
        }, 5000);
    }
}