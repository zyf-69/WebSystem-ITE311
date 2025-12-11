#!/bin/bash
# Git commit and push script for Git Bash
# Usage: bash commit_and_push.sh

echo "=== Git Commit and Push Script ==="
echo ""

# Check if we're in a git repository
if [ ! -d .git ]; then
    echo "ERROR: Not a git repository!"
    echo "Please run 'git init' first or navigate to a git repository."
    exit 1
fi

# Check current branch
CURRENT_BRANCH=$(git branch --show-current)
echo "Current branch: $CURRENT_BRANCH"
echo ""

# Pull remote changes first
echo "=== Pulling remote changes ==="
git pull origin main --no-rebase
if [ $? -ne 0 ]; then
    echo "Warning: Pull failed or conflicts detected. Continuing anyway..."
    echo ""
fi

# Show status
echo "=== Git Status ==="
git status
echo ""

# Add all files
echo "=== Adding all files ==="
git add .
echo "Files added!"
echo ""

# Commit message
COMMIT_MSG="Update: Enhanced enrollment system, unified dashboard, and improved course management

- Implemented teacher approval system for student enrollments
- Added unified dashboard for all user roles
- Enhanced admin course management with schedule and assignment features
- Improved My Courses page for both teachers and students (/my-course)
- Updated routes to simplified URLs (/my-course, /my-students)
- Added comprehensive input validation for names and emails
- Fixed access control and routing issues
- Created student my_courses view
- Updated EnrollmentModel to include all course fields"

echo "=== Committing changes ==="
echo "Commit message:"
echo "$COMMIT_MSG"
echo ""

git commit -m "$COMMIT_MSG"

if [ $? -eq 0 ]; then
    echo "Changes committed successfully!"
else
    echo "Commit failed or no changes to commit."
fi
echo ""

# Push to remote
echo "=== Pushing to GitHub ==="
git push -u origin main

if [ $? -eq 0 ]; then
    echo ""
    echo "=== SUCCESS ==="
    echo "All changes have been pushed to GitHub!"
    echo "Repository: https://github.com/zyf-69/WebSystem-ITE311.git"
else
    echo ""
    echo "=== WARNING ==="
    echo "Push may have failed. Please check the error messages above."
fi

echo ""
echo "Script completed!"

