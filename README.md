An extensible, defense‑in‑depth Intrusion Detection & Prevention module for Magento 2.


My initial release focuses on safe, composable primitives: request inspection, brute‑force throttling, IP blocking, honeypots, event logging, and admin/system configuration. It’s built to be production‑ready but conservative by default. You can extend each detector via DI.

## Module tree

Merlin/IntrusionDetection/
├── composer.json
├── registration.php
├── etc/
│ ├── module.xml
│ ├── db_schema.xml
│ ├── config.xml
│ ├── acl.xml
│ ├── adminhtml/
│ │ ├── system.xml
│ │ └── menu.xml
│ ├── di.xml
│ ├── events.xml
│ ├── crontab.xml
│ └── frontend/
│ └── routes.xml
├── Api/
│ ├── DetectorInterface.php
│ └── Data/
│ └── IntrusionEventInterface.php
├── Model/
│ ├── Config.php
│ ├── EventLogger.php
│ ├── EventLog.php
│ ├── ResourceModel/
│ │ ├── EventLog.php
│ │ └── EventLog/Collection.php
│ ├── BlockedIp.php
│ ├── ResourceModel/
│ │ ├── BlockedIp.php
│ │ └── BlockedIp/Collection.php
│ ├── Detection/
│ │ ├── IpBlockDetector.php
│ │ ├── RateLimiterDetector.php
│ │ ├── PathAnomalyDetector.php
│ │ ├── UserAgentDetector.php
│ │ ├── QueryRuleDetector.php
│ │ └── HoneypotDetector.php
│ ├── BlockService.php
│ ├── RateLimiter.php
│ └── Cron/
│ └── ExpireTemporaryBlocks.php
├── Plugin/
│ └── FrontController/IntrusionGuard.php
├── Observer/
│ ├── CustomerLoginFailed.php
│ └── AdminLoginFailed.php
├── Console/
│ ├── BlockIpCommand.php
│ ├── UnblockIpCommand.php
│ └── ListBlockedCommand.php
├── Controller/
│ └── Honeypot/Index.php
└── view/
└── frontend/
└── layout/default.xml

# Setup & usage


1) Deploy the module into `app/code/Merlin/IntrusionDetection`.
2) `bin/magento module:enable Merlin_IntrusionDetection && bin/magento setup:upgrade`
3) Configure in **Stores → Configuration → Security → Merlin Intrusion Detection**.
4) Start in **Detect** mode to observe events. Switch to **Block** to actively return HTTP 403 and optionally auto‑block offending IPs.
5) CLI helpers:
- `bin/magento merlin:id:block-ip 1.2.3.4 120 "manual test"`
- `bin/magento merlin:id:unblock-ip 1.2.3.4`
- `bin/magento merlin:id:list-blocked`



# Roadmap & Feature Ideas (extensible via additional Detectors)


- **GeoIP velocity checks**: sudden country jumps per session/IP.
- **Header sanity**: invalid Host, spoofed X-Forwarded-For, missing Accept headers.
- **GraphQL & REST hardening**: schema‑aware allowlists, depth/complexity limits.
- **File upload guard**: MIME sniffing, extension allowlist, scan via ClamAV/ICAP.
- **Admin path cloak**: randomize backend path detection + decoy endpoints.
- **2FA enforcement hooks**: deny risky IPs even pre‑auth.
- **Behavioral bot scoring**: sliding window + exponential backoff blocking.
- **Reputation lists**: optional integration with AbuseIPDB/Spamhaus (cachable, privacy‑aware).
- **CSP & security headers**: auto‑inject recommended headers with per‑route overrides.
- **Checkout abuse detection**: carding/BIN velocity, failed AVS/CVV spikes.
- **Captcha on demand**: trigger Captcha only when a risk score threshold is hit.
- **Webhook to SIEM**: stream `merlin_intrusion_event` to Splunk/ELK via queue.
- **Admin grids**: UI for Events & Blocked IPs with export, bulk actions.
- **Decoy admin users**: honey‑credentials to instantly flag attackers.
- **Inventory of scanners**: rolling UA/IP fingerprints to preemptively tarp.


# Notes
- This module is intentionally minimal in UI but strong in core mechanics.
- Avoid over‑blocking: keep **Detect** mode ON first, watch `merlin_intrusion_event`, then enable Blocking
- Ensure correct client IP: if behind HAProxy/Varnish, make sure Magento is reading `X-Forwarded-For` correctly (set `trusted_proxies`).
