# Vercel Deployment Setup Guide

## Problem
When importing from Git, Vercel looks at the repository root, not the `demo` folder.

## Solution: Set Root Directory in Vercel

### Step-by-Step Instructions:

1. **Push your code to GitHub**
   ```bash
   git add .
   git commit -m "Add demo folder"
   git push
   ```

2. **Go to Vercel Dashboard**
   - Visit https://vercel.com
   - Click "New Project" or select your existing project

3. **Import Repository**
   - Select your GitHub repository
   - Click "Import"

4. **Configure Project Settings**
   - **IMPORTANT**: In the project configuration:
     - **Root Directory**: Set to `demo`
     - **Framework Preset**: Leave as "Other" or "Static Site"
     - **Build Command**: Leave empty
     - **Output Directory**: Leave empty

5. **Deploy**
   - Click "Deploy"
   - Your demo will be live!

## Alternative: Create Separate Repository

If you prefer a cleaner setup:

1. **Create a new repository** (e.g., `smart-tutor-demo`)
2. **Copy only the demo folder contents**:
   ```bash
   cd demo
   git init
   git add .
   git commit -m "Initial demo"
   git remote add origin https://github.com/yourusername/smart-tutor-demo.git
   git push -u origin main
   ```
3. **Import this new repository** in Vercel
4. **No root directory configuration needed** - it's already the root!

## Alternative: Use Root vercel.json

If you want to keep everything in one repo:

1. The root `vercel.json` will redirect to `/demo/`
2. But you still need to set **Root Directory** to `demo` in Vercel settings

## Recommended Approach

**Best Option**: Set Root Directory to `demo` in Vercel project settings.

This is the cleanest solution and keeps your main project intact.

---

**Need Help?** Check Vercel docs: https://vercel.com/docs/concepts/projects/overview#root-directory

