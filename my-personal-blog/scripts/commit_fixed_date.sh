#!/usr/bin/env bash
# Commit helper that uses fixed Author/Committer date (2025-12-31T12:00:00+03:00)
DATE="2025-12-31T12:00:00+03:00"
GIT_AUTHOR_DATE="$DATE" GIT_COMMITTER_DATE="$DATE" git commit "$@"
