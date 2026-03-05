# Analytics Replay & Debugger

A CLI tool for debugging and replaying analytics events.

This tool simulates an analytics ingestion pipeline with event validation,
timeline inspection, funnel analysis, and replay to Mixpanel.

---

## Features

✔ Event Simulation  
✔ Event Timeline Inspection  
✔ Schema Validation  
✔ Funnel Analysis  
✔ Event Replay  
✔ User Journey Graph  
✔ Mixpanel Integration  

---

## Architecture


Then add this diagram:

Application
     ↓
Event Capture
     ↓
Schema Validator
     ↓
Duplicate Detector
     ↓
Event Store (SQLite)
     ↓
Replay Engine
     ↓
Mixpanel API

Continue README:

---

## Installation

Clone repository:

```bash
git clone https://github.com/YOUR_USERNAME/analytics-replay-debugger.git
cd analytics-replay-debugger
composer install
Environment Setup

Create .env file:

MIXPANEL_PROJECT_TOKEN=YOUR_TOKEN
ANALYTICS_ENABLED=true
CLI Commands
Simulate user journey
php bin/ard.php simulate --user=user_123 --company=c_101
Show timeline
php bin/ard.php timeline --user=user_123
Validate events
php bin/ard.php validate --user=user_123
Replay events to Mixpanel
php bin/ard.php replay --user=user_123
Inspect analytics
php bin/ard.php inspect --user=user_123
Funnel analysis
php bin/ard.php funnel --user=user_123
User journey graph
php bin/ard.php graph --user=user_123
Example Output
USER JOURNEY GRAPH

account_created
      ↓
vendor_invite_sent
      ↓
order_completed
Use Cases

This tool helps developers:

Debug analytics pipelines

Replay user events

Validate event schema

Analyze conversion funnels

Simulate user journeys

Technologies

PHP

SQLite

Mixpanel API

CLI Architecture

License

MIT


---

# 3️⃣ Add Architecture Diagram (better version)

You can include this in README:

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

This looks **very professional**.

---

# 4️⃣ CLI Usage Examples (Add Screenshot Section)

Add this to README:

```markdown
## CLI Usage Examples

Simulate user events:


php bin/ard.php simulate --user=user_123 --company=c_101


View user timeline:


php bin/ard.php timeline --user=user_123


Replay events:


php bin/ard.php replay --user=user_123

5️⃣ Final Result

Your GitHub project will look like:

analytics-replay-debugger
 ├── bin
 │   └── ard.php
 ├── src
 │   ├── CLI
 │   ├── Core
 │   ├── Mixpanel
 │   ├── Storage
 │   ├── Validation
 │   └── Simulate
 ├── storage
 ├── README.md
 ├── composer.json
 └── .env
