# Fix for Duplicate Username Error

## Problem
When registering a parent with the same username as a student, you get:
```
Fatal error: Duplicate entry 'shash' for key 'username'
```

This happens because the database has a UNIQUE constraint on the `username` column alone, preventing the same username for different roles.

## Solution

The database schema needs to be updated to allow the same username for different roles (student and parent can share username), but prevent duplicates within the same role.

### Step 1: Run SQL Fix

Open phpMyAdmin and run this SQL:

```sql
USE smart_tutor;

-- Remove the unique constraint on username alone
ALTER TABLE users DROP INDEX username;

-- Add composite unique constraint (username, role)
ALTER TABLE users ADD UNIQUE KEY unique_username_role (username, role);
```

**OR** if the table doesn't exist yet, the updated `database.sql` file already has the correct schema.

### Step 2: Verify

After running the SQL:
1. Try registering a student with username "test_student"
2. Try registering a parent with username "test_student" (should work now!)
3. Try registering another student with "test_student" (should fail - duplicate for same role)

### What Changed

**Before:**
- `username VARCHAR(50) UNIQUE` - Only one user could have a username, regardless of role

**After:**
- `UNIQUE KEY unique_username_role (username, role)` - Same username allowed for different roles, but not for the same role

### Example

✅ **Allowed:**
- Student: username="john_doe", role="student"
- Parent: username="john_doe", role="parent" ✓

❌ **Not Allowed:**
- Student: username="john_doe", role="student"
- Student: username="john_doe", role="student" ✗ (duplicate)

This allows parents to use their child's username while maintaining data integrity!

