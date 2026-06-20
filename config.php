<?php
// config.php
// Central place for secrets. Add this file to .gitignore before pushing to GitHub.

// ---------------------------------------------------------------
// NetAI Chatbot — choose ONE provider and paste your key below.
// Leave both empty to keep the chatbot in offline/demo mode.
// ---------------------------------------------------------------

// Option A: OpenAI (https://platform.openai.com/api-keys)
define('TN_OPENAI_API_KEY', ''); // e.g. 'sk-proj-xxxxxxxxxxxx'
define('TN_OPENAI_MODEL', 'gpt-4o-mini');

// Option B: Google Gemini (https://aistudio.google.com/app/apikey)
define('TN_GEMINI_API_KEY', ''); // e.g. 'AIzaSyxxxxxxxxxxxx'
define('TN_GEMINI_MODEL', 'gemini-1.5-flash');

// Which provider to use: 'openai', 'gemini', or 'none'
define('TN_AI_PROVIDER', 'none');