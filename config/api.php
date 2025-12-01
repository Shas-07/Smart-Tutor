<?php
// OpenRouter API Configuration
// Get your free API key from: https://openrouter.ai/keys

// IMPORTANT: Replace 'YOUR_OPENROUTER_API_KEY' with your actual API key
// Sign up at https://openrouter.ai/ to get a free API key

define('OPENROUTER_API_KEY', 'add your api key');

// OpenRouter API endpoint
define('OPENROUTER_API_URL', 'https://openrouter.ai/api/v1/chat/completions');

// Model to use (free models available)
// Options: 
// - "meta-llama/llama-3.2-3b-instruct:free" (fast, free)
// - "google/gemini-flash-1.5:free" (good quality, free)
// - "qwen/qwen-2.5-7b-instruct:free" (alternative free option)
define('OPENROUTER_MODEL', 'meta-llama/llama-3.2-3b-instruct:free');

// System prompt for the AI assistant
define('AI_SYSTEM_PROMPT', 'You are a helpful AI study assistant for Smart Tutor, an educational platform. Help students with their studies, answer questions about courses, materials, formulas, homework, and provide educational guidance. Be friendly, encouraging, and educational.');

?>


