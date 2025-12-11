# Git Commit Instructions

## Quick Commit (Using Git Bash)

1. Open **Git Bash** in the project directory: `C:\xampp\htdocs\ITE311-DIGA`

2. Run the commit script:
   ```bash
   bash commit_and_push.sh
   ```

## Manual Commit Steps

If the script doesn't work, follow these steps manually:

### Step 1: Pull remote changes
```bash
git pull origin main --no-rebase
```

### Step 2: Add all files
```bash
git add .
```

### Step 3: Commit with message
```bash
git commit -m "Update: Enhanced enrollment system, unified dashboard, and improved course management

- Implemented teacher approval system for student enrollments
- Added unified dashboard for all user roles
- Enhanced admin course management with schedule and assignment features
- Improved My Courses page for both teachers and students (/my-course)
- Updated routes to simplified URLs (/my-course, /my-students)
- Added comprehensive input validation for names and emails
- Fixed access control and routing issues
- Created student my_courses view
- Updated EnrollmentModel to include all course fields"
```

### Step 4: Push to GitHub
```bash
git push -u origin main
```

## If Push is Rejected

If you get a "rejected" error, you have two options:

### Option A: Merge remote changes (Recommended)
```bash
git pull origin main --no-rebase
# Resolve any conflicts if prompted
git add .
git commit -m "Merge remote changes"
git push origin main
```

### Option B: Force push (Use with caution - overwrites remote)
```bash
git push -f origin main
```

**Warning:** Force push will overwrite the remote repository. Only use if you're sure you want to replace all remote changes.

## Repository URL
https://github.com/zyf-69/WebSystem-ITE311.git

