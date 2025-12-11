# PowerShell script to commit and push changes to GitHub
# Usage: .\COMMIT_CHANGES.ps1

Write-Host "=== Git Commit and Push Script ===" -ForegroundColor Cyan
Write-Host ""

# Check if git is installed
$gitInstalled = Get-Command git -ErrorAction SilentlyContinue
if (-not $gitInstalled) {
    Write-Host "ERROR: Git is not installed or not in PATH!" -ForegroundColor Red
    Write-Host "Please install Git from https://git-scm.com/downloads" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "After installing Git, restart your terminal and run this script again." -ForegroundColor Yellow
    exit 1
}

Write-Host "Git found: $($gitInstalled.Source)" -ForegroundColor Green
Write-Host ""

# Check if .git exists
if (-not (Test-Path .git)) {
    Write-Host "Initializing Git repository..." -ForegroundColor Yellow
    git init
    Write-Host ""
}

# Set remote repository
$remoteUrl = "https://github.com/zyf-69/WebSystem-ITE311.git"
Write-Host "Setting remote repository..." -ForegroundColor Yellow
$existingRemote = git remote get-url origin -ErrorAction SilentlyContinue
if ($existingRemote) {
    Write-Host "Remote 'origin' already exists: $existingRemote" -ForegroundColor Cyan
    $changeRemote = Read-Host "Do you want to change it to $remoteUrl? (y/n)"
    if ($changeRemote -eq 'y' -or $changeRemote -eq 'Y') {
        git remote set-url origin $remoteUrl
        Write-Host "Remote updated!" -ForegroundColor Green
    }
} else {
    git remote add origin $remoteUrl
    Write-Host "Remote 'origin' added!" -ForegroundColor Green
}
Write-Host ""

# Check current branch
$currentBranch = git branch --show-current
if (-not $currentBranch) {
    Write-Host "Creating 'main' branch..." -ForegroundColor Yellow
    git checkout -b main
    $currentBranch = "main"
}
Write-Host "Current branch: $currentBranch" -ForegroundColor Cyan
Write-Host ""

# Show status
Write-Host "=== Git Status ===" -ForegroundColor Cyan
git status
Write-Host ""

# Add all files
Write-Host "Adding all files..." -ForegroundColor Yellow
git add .
Write-Host "Files added!" -ForegroundColor Green
Write-Host ""

# Commit message
$commitMessage = "Update: Enhanced enrollment system, unified dashboard, and improved course management

- Implemented teacher approval system for student enrollments
- Added unified dashboard for all user roles
- Enhanced admin course management with schedule and assignment features
- Improved My Courses page for both teachers and students
- Updated routes to simplified URLs (/my-course, /my-students)
- Added comprehensive input validation
- Fixed access control and routing issues"

Write-Host "Committing changes..." -ForegroundColor Yellow
Write-Host "Commit message:" -ForegroundColor Cyan
Write-Host $commitMessage -ForegroundColor Gray
Write-Host ""

git commit -m $commitMessage
if ($LASTEXITCODE -eq 0) {
    Write-Host "Changes committed successfully!" -ForegroundColor Green
} else {
    Write-Host "Commit failed or no changes to commit." -ForegroundColor Yellow
}
Write-Host ""

# Push to remote
Write-Host "Pushing to GitHub..." -ForegroundColor Yellow
$pushBranch = Read-Host "Push to branch (default: $currentBranch)"
if ([string]::IsNullOrWhiteSpace($pushBranch)) {
    $pushBranch = $currentBranch
}

# Check if branch exists on remote
$remoteBranch = git ls-remote --heads origin $pushBranch
if ($remoteBranch) {
    Write-Host "Branch '$pushBranch' exists on remote. Pushing..." -ForegroundColor Cyan
    git push -u origin $pushBranch
} else {
    Write-Host "Branch '$pushBranch' does not exist on remote. Creating and pushing..." -ForegroundColor Cyan
    git push -u origin $pushBranch
}

if ($LASTEXITCODE -eq 0) {
    Write-Host ""
    Write-Host "=== SUCCESS ===" -ForegroundColor Green
    Write-Host "All changes have been pushed to GitHub!" -ForegroundColor Green
    Write-Host "Repository: $remoteUrl" -ForegroundColor Cyan
} else {
    Write-Host ""
    Write-Host "=== WARNING ===" -ForegroundColor Yellow
    Write-Host "Push may have failed. Please check the error messages above." -ForegroundColor Yellow
    Write-Host "You may need to set up authentication or pull first." -ForegroundColor Yellow
}

Write-Host ""
Write-Host "Script completed!" -ForegroundColor Cyan

