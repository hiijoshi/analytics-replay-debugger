# Analytics Replay & Debugger

![PHP](https://img.shields.io/badge/PHP-8.1+-blue)
![CLI](https://img.shields.io/badge/Interface-CLI-green)
![Database](https://img.shields.io/badge/Database-SQLite-lightgrey)
![Analytics](https://img.shields.io/badge/Analytics-Mixpanel-purple)
![License](https://img.shields.io/badge/License-MIT-yellow)

Analytics Replay & Debugger is a **PHP CLI tool** designed to help developers debug, validate, and replay analytics events.

It simulates how analytics events flow through a system and allows developers to inspect event timelines, validate schemas, analyze funnels, and replay events directly to **Mixpanel**.

This project helps developers test analytics tracking without using real production data.

---

# Preview

Example CLI output:

```text
USER JOURNEY GRAPH

account_created
      ↓
vendor_invite_sent
      ↓
api_key_generated
      ↓
marketplace_integration_connected
      ↓
order_completed
```

---

# Features

- Event Simulation
- Event Timeline Inspection
- Schema Validation
- Duplicate Event Detection
- Funnel Analysis
- Event Replay to Mixpanel
- User Journey Graph
- SQLite Event Storage
- CLI Analytics Debugging

---

# Architecture

This tool simulates a simple analytics pipeline.

```text
+-------------------+
|   Application     |
+-------------------+
         |
         v
+-------------------+
|  Event Capture    |
+-------------------+
         |
         v
+-------------------+
| Schema Validator  |
+-------------------+
         |
         v
+-------------------+
| Duplicate Detector|
+-------------------+
         |
         v
+-------------------+
| Event Store       |
|   (SQLite)        |
+-------------------+
         |
         v
+-------------------+
| Replay Engine     |
+-------------------+
         |
         v
+-------------------+
|  Mixpanel API     |
+-------------------+
```

### Pipeline Explanation

| Component | Purpose |
|--------|--------|
Application | Source where analytics events are generated |
Event Capture | Collects events |
Schema Validator | Ensures event format is correct |
Duplicate Detector | Prevents duplicate events |
Event Store | Saves events locally in SQLite |
Replay Engine | Replays stored events |
Mixpanel API | Sends events to Mixpanel |

---

# Project Structure

```text
analytics-replay-debugger
│
├── bin
│   └── ard.php
│
├── src
│   ├── CLI
│   ├── Core
│   ├── Mixpanel
│   ├── Storage
│   ├── Validation
│   └── Simulate
│
├── storage
│
├── README.md
├── composer.json
└── .env
```

### Folder Details

| Folder | Description |
|------|-------------|
bin | CLI entry point |
src/CLI | CLI command logic |
src/Core | Core analytics logic |
src/Mixpanel | Mixpanel integration |
src/Storage | SQLite storage |
src/Validation | Event validation |
src/Simulate | Event simulation |
storage | SQLite database files |

---

# Requirements

Before running the project make sure you have:

- PHP 8.1 or higher
- Composer
- SQLite
- Mixpanel Project Token

---

# Installation

Clone the repository:

```bash
git clone https://github.com/YOUR_USERNAME/analytics-replay-debugger.git
cd analytics-replay-debugger
```

Install dependencies:

```bash
composer install
```

---

# Environment Setup

Create a `.env` file in the project root:

```
MIXPANEL_PROJECT_TOKEN=YOUR_TOKEN
ANALYTICS_ENABLED=true
DB_PATH=storage/events.sqlite
```

Replace `YOUR_TOKEN` with your Mixpanel project token.

---

# CLI Commands

## Simulate User Journey

Generate test analytics events.

```bash
php bin/ard.php simulate --user=user_123 --company=c_101
```

This creates a simulated journey such as:

```
signup → invite → order_complete
```

---

## Show Event Timeline

View events in chronological order.

```bash
php bin/ard.php timeline --user=user_123
```

Example output:

```
[2026-03-06 10:30:12] account_created
[2026-03-06 10:31:10] vendor_invite_sent
[2026-03-06 10:35:45] order_completed
```

---

## Validate Events

Check event schema validity.

```bash
php bin/ard.php validate --user=user_123
```

Validation checks:

- event name exists
- user id exists
- timestamp exists
- required properties exist

---

## Replay Events

Send stored events to Mixpanel.

```bash
php bin/ard.php replay --user=user_123
```

This helps test analytics pipelines.

---

## Inspect Events

View full event details.

```bash
php bin/ard.php inspect --user=user_123
```

Example:

```
Event: account_created
User: user_123
Company: c_101

Properties
- plan: free
- country: IN
- source: cli
- timestamp: 2026-03-06 10:30:12
```

---

## Funnel Analysis

Analyze user conversion flow.

```bash
php bin/ard.php funnel --user=user_123
```

Example funnel:

```
account_created
 → api_key_generated
 → marketplace_integration_connected
 → order_completed
```

---

## User Journey Graph

Visualize event sequence.

```bash
php bin/ard.php graph --user=user_123
```

Output example:

```
account_created
      ↓
vendor_invite_sent
      ↓
api_key_generated
      ↓
marketplace_integration_connected
      ↓
order_completed
```

---

# Example Analytics Events

Some supported analytics events:

- account_created
- vendor_invite_sent
- vendor_invite_accepted
- api_key_generated
- marketplace_integration_connected
- marketplace_integration_failed
- order_sync_started
- order_sync_completed
- order_sync_failed
- vendor_payout_initiated
- vendor_payout_failed
- product_import_started
- product_import_completed
- product_import_failed
- activation_achieved

---

# Use Cases

This tool helps developers:

### Debug Analytics Pipelines
Trace issues when events are missing.

### Replay Events
Replay previously stored events.

### Validate Event Structure
Ensure analytics schema is correct.

### Analyze Conversion Funnels
Identify user drop-off points.

### Test Mixpanel Integration
Verify events reach Mixpanel.

### Simulate User Journeys
Useful for development and testing.

---

# How It Works

1. CLI generates an event  
2. Event is captured by system  
3. Schema validation checks event format  
4. Duplicate detection prevents repeated events  
5. Event stored in SQLite  
6. Replay engine sends events again  
7. Mixpanel receives events through API  

---

# Technologies Used

- PHP
- SQLite
- Mixpanel API
- Composer
- CLI Architecture

---

# Why This Project Is Useful

Analytics debugging can be difficult because:

- events don't appear in Mixpanel
- properties are incorrect
- duplicate events are sent
- funnels break
- developers cannot trace the pipeline

This tool provides a **local analytics debugging environment**.

---

# Future Improvements

Possible upgrades:

- Web dashboard for analytics inspection
- Export events to JSON or CSV
- Configurable event schema
- Retry queue for failed events
- Multi-user replay
- Visual analytics dashboard
- Support for other analytics platforms



---

# Quick Start

Run these commands to test quickly:

```bash
git clone https://github.com/YOUR_USERNAME/analytics-replay-debugger.git
cd analytics-replay-debugger
composer install

php bin/ard.php simulate --user=user_123 --company=c_101
php bin/ard.php timeline --user=user_123
php bin/ard.php replay --user=user_123
```

---

# Summary

Analytics Replay & Debugger is a developer tool that helps:

- simulate analytics events
- inspect event timelines
- validate schemas
- analyze funnels
- replay events to Mixpanel
