# 🚀 Quick Deploy Guide - Fix "Can't Find Demo Folder"

## The Problem
When you import your Git repository in Vercel, it looks at the **root** directory, not the `demo` folder.

## ✅ Solution: Set Root Directory

### Step 1: Push to GitHub
```bash
git add .
git commit -m "Add demo folder"
git push
```

### Step 2: Import in Vercel
1. Go to https://vercel.com
2. Click **"New Project"**
3. Import your GitHub repository

### Step 3: Configure Root Directory ⚠️ IMPORTANT
1. In the **"Configure Project"** screen, look for **"Root Directory"**
2. Click **"Edit"** (or the pencil icon)
3. Type: `demo`
4. Click **"Continue"** or **"Save"**

### Step 4: Deploy
- Click **"Deploy"**
- Done! ✅

## 🔧 If Already Deployed

If you already deployed and it's showing the wrong folder:

1. Go to your **Vercel Dashboard**
2. Select your **project**
3. Go to **Settings** → **General**
4. Scroll to **"Root Directory"**
5. Click **"Edit"**
6. Enter: `demo`
7. Click **"Save"**
8. Go to **Deployments** tab
9. Click **"Redeploy"** on the latest deployment

## 📸 Visual Guide

```
Vercel Import Screen:
┌─────────────────────────────────┐
│ Configure Project               │
├─────────────────────────────────┤
│ Framework Preset: [Other ▼]    │
│ Root Directory: [demo] ← SET THIS!
│ Build Command: [empty]           │
│ Output Directory: [empty]       │
└─────────────────────────────────┘
```

## ✅ Verify It's Working

After deployment, your site should show:
- Login page (not the PHP project)
- Demo credentials work
- No backend errors

## 🆘 Still Not Working?

**Option A: Create Separate Repository**
```bash
cd demo
git init
git add .
git commit -m "Demo version"
git remote add origin https://github.com/yourusername/smart-tutor-demo.git
git push -u origin main
```
Then import this new repo - no root directory needed!

**Option B: Check File Structure**
Make sure `demo/index.html` exists in your repository.

---

**That's it! The key is setting Root Directory to `demo` in Vercel settings.** 🎯

