Analytics Replay & Debugger

Analytics Replay & Debugger is a CLI tool built in PHP that helps developers debug, inspect, and replay analytics events.

It simulates how analytics events move through a system and allows developers to test if events are correctly sent to Mixpanel.

This tool is useful when developers want to test analytics tracking without using real production data.

Features

Event Simulation – simulate user actions

Event Timeline – see events in order

Schema Validation – check if events follow correct format

Duplicate Detection – detect repeated events

Funnel Analysis – see user conversion flow

Event Replay – resend events to Mixpanel

User Journey Graph – visualize user activity

Mixpanel Integration – send analytics data to Mixpanel

Architecture

This tool follows a simple analytics pipeline.

Application
     |
     v
Event Capture
     |
     v
Schema Validator
     |
     v
Duplicate Detector
     |
     v
Event Store (SQLite)
     |
     v
Replay Engine
     |
     v
Mixpanel API

Explanation:

Application – where events are generated

Event Capture – collects events

Schema Validator – checks event structure

Duplicate Detector – prevents repeated events

Event Store – saves events in SQLite database

Replay Engine – resends events for testing

Mixpanel API – sends events to Mixpanel

Project Structure
analytics-replay-debugger

bin/
 └ ard.php            CLI entry file

src/
 ├ CLI                command logic
 ├ Core               main analytics logic
 ├ Mixpanel           Mixpanel integration
 ├ Storage            SQLite storage
 ├ Validation         event validation
 └ Simulate           event simulation

storage/              database files

composer.json
README.md
.env
Installation

Clone the project

git clone https://github.com/YOUR_USERNAME/analytics-replay-debugger.git
cd analytics-replay-debugger

Install dependencies

composer install
Environment Setup

Create a .env file.

MIXPANEL_PROJECT_TOKEN=YOUR_TOKEN
ANALYTICS_ENABLED=true

Replace YOUR_TOKEN with your Mixpanel project token.

CLI Commands
Simulate user journey
php bin/ard.php simulate --user=user_123 --company=c_101

This command creates test events for a user.

View event timeline
php bin/ard.php timeline --user=user_123

Shows all events in order for the user.

Validate events
php bin/ard.php validate --user=user_123

Checks if events follow the correct schema.

Replay events
php bin/ard.php replay --user=user_123

Sends stored events to Mixpanel.

Inspect analytics events
php bin/ard.php inspect --user=user_123

Shows detailed event information.

Funnel analysis
php bin/ard.php funnel --user=user_123

Analyzes user conversion steps.

User journey graph
php bin/ard.php graph --user=user_123

Example output

USER JOURNEY

account_created
   ↓
vendor_invite_sent
   ↓
order_completed
Use Cases

This tool helps developers:

Debug analytics tracking

Test analytics pipelines

Replay user events

Validate event schema

Analyze funnels

Verify Mixpanel integration

Technologies Used

PHP

SQLite

Mixpanel API

CLI Architecture
