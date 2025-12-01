# OpenRouter API Setup Guide

## What is OpenRouter?

OpenRouter is a unified API for accessing multiple AI models, including free models. It's perfect for educational applications like Smart Tutor.

## Step 1: Get Your Free API Key

1. Visit **https://openrouter.ai/**
2. Click **"Sign Up"** or **"Get Started"**
3. Create a free account (no credit card required for free tier)
4. Go to **https://openrouter.ai/keys**
5. Click **"Create Key"**
6. Copy your API key (it starts with `sk-or-v1-...`)

## Step 2: Configure the API Key

1. Open `config/api.php` in your project
2. Find this line:
   ```php
   define('OPENROUTER_API_KEY', 'YOUR_OPENROUTER_API_KEY');
   ```
3. Replace `YOUR_OPENROUTER_API_KEY` with your actual API key:
   ```php
   define('OPENROUTER_API_KEY', 'sk-or-v1-your-actual-key-here');
   ```
4. Save the file

## Step 3: Choose a Model (Optional)

The default model is set to `meta-llama/llama-3.2-3b-instruct:free` which is free and fast.

You can change it in `config/api.php`:

```php
// Free model options:
define('OPENROUTER_MODEL', 'meta-llama/llama-3.2-3b-instruct:free'); // Fast, free
// OR
define('OPENROUTER_MODEL', 'google/gemini-flash-1.5:free'); // Good quality, free
// OR
define('OPENROUTER_MODEL', 'qwen/qwen-2.5-7b-instruct:free'); // Alternative free option
```

## Step 4: Test the Integration

1. Login as a student
2. Open the AI chat (bottom-right corner)
3. Type a message like "Hello" or "Help me with math"
4. You should get a real AI response!

## Free Tier Limits

OpenRouter's free tier includes:
- ✅ Free access to several models
- ✅ No credit card required
- ✅ Reasonable rate limits for educational use
- ⚠️ Rate limits apply (check OpenRouter dashboard)

## Troubleshooting

### Error: "API key not configured"
- Make sure you've replaced `YOUR_OPENROUTER_API_KEY` in `config/api.php`
- Check that the API key is correct (starts with `sk-or-v1-`)

### Error: "API request failed"
- Check your internet connection
- Verify the API key is valid at https://openrouter.ai/keys
- Check if you've hit rate limits

### Fallback Mode
If the API fails, the chat will automatically use static responses as a fallback, so the feature still works!

## Security Note

✅ The API key is stored server-side in `config/api.php`  
✅ API calls are made through PHP backend (not directly from JavaScript)  
✅ This keeps your API key secure and prevents exposure

## Need Help?

- OpenRouter Docs: https://openrouter.ai/docs
- Check OpenRouter Dashboard: https://openrouter.ai/activity
- View available models: https://openrouter.ai/models

---

**That's it! Your AI chat is now powered by real AI!** 🚀

