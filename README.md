# elementor-hosting-pricing
A custom Elementor widget for displaying hosting pricing plans with monthly/annual toggling functionality.

![Demo](docs/screenshot-1.png)

## Features
- Per-instance JS initialization (works with multiple widgets on the same page)
- Monthly/Annual billing toggle with keyboard and ARIA support
- Auto-calculated “Billed as … per year” and “Save …/year” when Annual is active
- Elementor controls for plan name, subtitle, prices, CTA, and featured badge
- Translation-ready (`languages/elementor-hosting-pricing.pot`)
- Lightweight, no external build step required

## Requirements
- WordPress 5.8+
- PHP 7.4+
- Elementor 3.0.0+
- jQuery (bundled with WordPress)

## Installation
1. Download the latest release ZIP from GitHub Releases.
2. In WordPress Admin → Plugins → Add New → Upload Plugin → choose the ZIP → Install → Activate.
3. Ensure Elementor is installed and active.

## Usage (Elementor)
1. Open a page in Elementor.
2. Search for “Hosting Pricing” widget and drag it into your layout.
3. Add one or more plans under “Pricing Plans”.
4. For each plan:
   - Plan Name: e.g., Basic, Pro, Business
   - Subtitle: short description (limited HTML allowed)
   - Monthly Price: per-month price when billed monthly
   - Annual Price: discounted per-month price when billed yearly (UI multiplies by 12 for total)
   - Button Text + Button URL (use the gear icon to set target and nofollow)
   - Featured Plan: visually highlights the card
5. Style options are available under the Style tab (colors, borders, buttons, etc.).

## Example plans (copy into the repeater)
- Basic
  - Subtitle: “Web Presence: Ideal for a basic and effective site.”
  - Monthly Price: 24.99
  - Annual Price: 19.99
  - Button: “Order Now” → https://your-link.com
- Pro (Featured)
  - Subtitle: “E‑commerce Launch: Start selling online.”
  - Monthly Price: 34.99
  - Annual Price: 29.99
  - Button: “Order Now” → https://your-link.com
- Business
  - Subtitle: “Comprehensive Presence: All‑in‑one plan for growth.”
  - Monthly Price: 49.99
  - Annual Price: 39.99
  - Button: “Order Now” → https://your-link.com

## Accessibility
- Toggle labels are focusable and respond to Enter/Space
- `aria-pressed` reflects the active billing option
- Checkbox has `aria-label` and updates state on `change`

## Internationalization
- Text domain: `elementor-hosting-pricing`
- POT file: `languages/elementor-hosting-pricing.pot`
- Use standard WordPress translation tools (e.g., Poedit/Loco Translate)

## Development
- Main plugin: `elementor-hosting-pricing.php`
- Widget: `widgets/hosting-pricing-widget.php`
- Assets: `assets/css/hosting-pricing.css`, `assets/js/hosting-pricing.js`

## Releases (GitHub Actions)
- Creating a GitHub Release with a tag (e.g., `v1.0.0`) triggers CI to build and attach:
  - `elementor-hosting-pricing-vX.Y.Z.zip`
- Workflow: `.github/workflows/ci-release.yml`

Quick commands:
```bash
git add .
git commit -m "chore: prepare release v1.0.0"
git push -u origin main
git tag v1.0.0
git push origin v1.0.0
# Create Release via GitHub UI or API
```

## Changelog
See [CHANGELOG.md](./CHANGELOG.md)

## License
MIT. See [LICENSE](./LICENSE).
