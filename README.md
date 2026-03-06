# Analytics Replay & Debugger

Analytics Replay & Debugger is a simple **PHP CLI tool** for testing, debugging, and replaying analytics events.

It helps developers understand how analytics events move through a system. You can simulate user actions, store events, validate them, inspect event timelines, and replay them to **Mixpanel**.

This project is useful for:

- testing analytics tracking
- checking event structure
- debugging missing events
- replaying stored events
- analyzing user journeys

---

## Features

- **Event Simulation** – create test user events
- **Event Timeline** – see all events in order
- **Schema Validation** – check event format
- **Duplicate Detection** – find repeated events
- **Funnel Analysis** – understand conversion flow
- **Event Replay** – resend events to Mixpanel
- **User Journey Graph** – view event sequence
- **Mixpanel Integration** – connect with Mixpanel API
- **SQLite Storage** – save events locally

---

## Architecture

This tool follows a simple analytics pipeline:


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
# Flow Explanation

Application: the source where events happen

Event Capture: collects analytics events

Schema Validator: checks event structure

Duplicate Detector: stops repeated events

Event Store: saves events in SQLite

Replay Engine: replays saved events

Mixpanel API: sends events to Mixpanel

# Project Structure
analytics-replay-debugger/
├── bin/
│   └── ard.php
├── src/
│   ├── CLI/
│   ├── Core/
│   ├── Mixpanel/
│   ├── Storage/
│   ├── Validation/
│   └── Simulate/
├── storage/
├── README.md
├── composer.json
└── .env
Folder Details

bin/ → CLI entry file

src/CLI/ → command handling

src/Core/ → main business logic

src/Mixpanel/ → Mixpanel API integration

src/Storage/ → SQLite event storage

src/Validation/ → schema and event checks

src/Simulate/ → test event generation

storage/ → local database files

# Requirements

Before running this project, make sure you have:

PHP 8.1 or higher

Composer

SQLite

Mixpanel project token

# Installation

Clone the repository:

git clone https://github.com/YOUR_USERNAME/analytics-replay-debugger.git
cd analytics-replay-debugger

# Install dependencies:

composer install
Environment Setup

Create a .env file in the project root:

MIXPANEL_PROJECT_TOKEN=YOUR_TOKEN
ANALYTICS_ENABLED=true
DB_PATH=storage/events.sqlite

Replace YOUR_TOKEN with your real Mixpanel project token.

CLI Commands
1. Simulate User Journey

Use this command to create sample analytics events for a user.

php bin/ard.php simulate --user=user_123 --company=c_101

Example purpose:
Generate a fake journey like signup → invite → order complete.

2. Show Timeline

Display all events of a user in time order.

php bin/ard.php timeline --user=user_123

Example output:

[2026-03-06 10:30:12] account_created
[2026-03-06 10:31:10] vendor_invite_sent
[2026-03-06 10:35:45] order_completed
3. Validate Events

Check whether saved events follow the required schema.

php bin/ard.php validate --user=user_123

What it checks:

event name exists

user id exists

timestamp exists

required properties are present

format is correct

4. Replay Events to Mixpanel

Resend user events from local storage to Mixpanel.

php bin/ard.php replay --user=user_123

This is useful for testing analytics tracking without repeating real user actions.

5. Inspect Analytics Events

Show detailed event data for a user.

php bin/ard.php inspect --user=user_123

Example output:

Event: account_created
User: user_123
Company: c_101
Properties:
- plan: free
- country: IN
- source: cli
- timestamp: 2026-03-06 10:30:12
6. Funnel Analysis

Check how users move through important conversion steps.

php bin/ard.php funnel --user=user_123

Example funnel:

account_created -> api_key_generated -> marketplace_integration_connected -> order_completed
7. User Journey Graph

Visualize the user event sequence.

php bin/ard.php graph --user=user_123

Example output:

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
CLI Usage Examples
Simulate user events
php bin/ard.php simulate --user=user_123 --company=c_101
View user timeline
php bin/ard.php timeline --user=user_123
Validate event schema
php bin/ard.php validate --user=user_123
Replay events
php bin/ard.php replay --user=user_123
Inspect events
php bin/ard.php inspect --user=user_123
Funnel analysis
php bin/ard.php funnel --user=user_123
Graph user journey
php bin/ard.php graph --user=user_123
Example User Journey

A sample user journey can look like this:

account_created
      ↓
vendor_invite_sent
      ↓
vendor_invite_accepted
      ↓
api_key_generated
      ↓
marketplace_integration_connected
      ↓
order_sync_started
      ↓
order_sync_completed

This helps developers understand the full event flow.

Example Events

These are some example analytics events that can be stored or replayed:

account_created

vendor_invite_sent

vendor_invite_accepted

api_key_generated

marketplace_integration_connected

marketplace_integration_failed

order_sync_started

order_sync_completed

order_sync_failed

vendor_payout_initiated

vendor_payout_failed

product_import_started

product_import_completed

product_import_failed

activation_achieved

Use Cases

This tool helps developers in many real situations:

Debug Analytics Pipelines

If analytics data is missing, delayed, or incorrect, this tool helps trace the problem.

Replay User Events

If some events were not sent earlier, they can be replayed from storage.

Validate Event Structure

Developers can make sure all events follow the correct schema.

Analyze User Funnels

Teams can understand where users drop in a journey.

Test Mixpanel Integration

You can verify that events are correctly reaching Mixpanel.

Simulate Real User Journeys

Useful in development, QA testing, demos, and debugging.

How It Works

A test event is generated from the CLI

The event is captured by the system

Schema validation checks the event format

Duplicate detection checks repeated events

The event is stored in SQLite

The replay engine can resend it

Mixpanel receives the event through API

Technologies Used

PHP – backend CLI development

SQLite – lightweight local event storage

Mixpanel API – analytics destination

Composer – dependency management

CLI Architecture – command-based interaction

Why This Project Is Useful

In many projects, analytics tracking becomes difficult to debug because:

events do not appear in Mixpanel

event properties are incomplete

duplicate events are sent

conversion steps break

developers do not know where the pipeline failed

This tool solves those problems by giving a local, testable analytics workflow.

Future Improvements

Possible future upgrades:

web dashboard for event inspection

export timeline to JSON or CSV

custom event schema config

retry queue for failed Mixpanel events

multi-user batch replay

event comparison reports

graphical dashboard for funnels

support for other analytics tools

License

MIT License

Author

Created for learning, debugging, and improving analytics workflows.

If you want to use this in production, you can extend it with:

authentication

queue support

better reporting

dashboard UI

automated testing

Quick Start

Run these commands to quickly test the project:

git clone https://github.com/YOUR_USERNAME/analytics-replay-debugger.git
cd analytics-replay-debugger
composer install
php bin/ard.php simulate --user=user_123 --company=c_101
php bin/ard.php timeline --user=user_123
php bin/ard.php replay --user=user_123
Final Summary
