<?php
// trust_data.php
function tn_get_domain_list() {
    return [
        'bankofbaroda.in'     => ['score' => 96, 'status' => 'verified', 'label' => 'Verified Bank',  'note' => 'Official Bank of Baroda domain.'],
        'uidai.gov.in'        => ['score' => 98, 'status' => 'verified', 'label' => 'Verified Govt.',  'note' => 'Official UIDAI (Aadhaar) government domain.'],
        'sbi.co.in'           => ['score' => 95, 'status' => 'verified', 'label' => 'Verified Bank',  'note' => 'Official State Bank of India domain.'],
        'onlinesbi.sbi'       => ['score' => 95, 'status' => 'verified', 'label' => 'Verified Bank',  'note' => 'Official SBI net-banking domain.'],
        'incometax.gov.in'    => ['score' => 97, 'status' => 'verified', 'label' => 'Verified Govt.',  'note' => 'Official Income Tax Department domain.'],
        'npci.org.in'         => ['score' => 96, 'status' => 'verified', 'label' => 'Verified',        'note' => 'Official NPCI (UPI) domain.'],
        'fake-bank-login.com' => ['score' => 8,  'status' => 'danger',   'label' => 'Phishing',         'note' => 'Reported phishing domain impersonating a bank.'],
        'sbi-kyc-update.in'   => ['score' => 5,  'status' => 'danger',   'label' => 'Phishing',         'note' => 'Not an official SBI domain. Likely KYC scam.'],
    ];
}

function tn_check_domain($input) {
    $input = trim($input);
    if ($input === '') {
        return null;
    }

    $host = $input;
    $host = preg_replace('#^https?://#i', '', $host);
    $host = preg_replace('#^www\.#i', '', $host);
    $host = explode('/', $host)[0];
    $host = explode('?', $host)[0];
    $host = strtolower(trim($host));

    $domains = tn_get_domain_list();

    if (isset($domains[$host])) {
        return array_merge(['domain' => $host], $domains[$host]);
    }

    return [
        'domain' => $host,
        'score'  => 35,
        'status' => 'unknown',
        'label'  => 'Unverified',
        'note'   => 'This domain is not in the TrustNet verified registry. Proceed with caution.',
    ];
}