An extensible, defense‑in‑depth Intrusion Detection & Prevention module for Magento 2.

#v1.1

Fix: Fix ACL Permissions for events and blocks

New: Added full admin grids showing events and blocked IP's

#v1.0

Initial Release

My initial release focuses on safe, composable primitives: request inspection, brute‑force throttling, IP blocking, honeypots, event logging, and admin/system configuration. It’s built to be production‑ready but conservative by default. You can extend each detector via DI.

# Setup & usage

1) Deploy the module into `app/code/Merlin/IntrusionDetection`.
2) `bin/magento module:enable Merlin_IntrusionDetection && bin/magento setup:upgrade`
3) Configure in **Stores → Configuration → Security → Merlin Intrusion Detection**.
4) Start in **Detect** mode to observe events. Switch to **Block** to actively return HTTP 403 and optionally auto‑block offending IPs.
5) CLI helpers:
- `bin/magento merlin:id:block-ip 1.2.3.4 120 "manual test"`
- `bin/magento merlin:id:unblock-ip 1.2.3.4`
- `bin/magento merlin:id:list-blocked`



# Roadmap & Feature Ideas


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
