---
name: Modern Pastel Tea System
colors:
  surface: '#fff8f6'
  surface-dim: '#e1d8d5'
  surface-bright: '#fff8f6'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#fbf2ef'
  surface-container: '#f5ece9'
  surface-container-high: '#efe6e3'
  surface-container-highest: '#e9e1de'
  on-surface: '#1e1b19'
  on-surface-variant: '#514347'
  inverse-surface: '#342f2e'
  inverse-on-surface: '#f8efec'
  outline: '#837377'
  outline-variant: '#d5c2c6'
  surface-tint: '#864d61'
  primary: '#864d61'
  on-primary: '#ffffff'
  primary-container: '#ffb7ce'
  on-primary-container: '#7b4458'
  inverse-primary: '#fab3ca'
  secondary: '#3a675a'
  on-secondary: '#ffffff'
  secondary-container: '#bceddc'
  on-secondary-container: '#406d60'
  tertiary: '#625e56'
  on-tertiary: '#ffffff'
  tertiary-container: '#d0cac1'
  on-tertiary-container: '#59554e'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffd9e3'
  primary-fixed-dim: '#fab3ca'
  on-primary-fixed: '#360b1e'
  on-primary-fixed-variant: '#6a364a'
  secondary-fixed: '#bceddc'
  secondary-fixed-dim: '#a1d0c1'
  on-secondary-fixed: '#002019'
  on-secondary-fixed-variant: '#214e43'
  tertiary-fixed: '#e8e2d8'
  tertiary-fixed-dim: '#ccc6bc'
  on-tertiary-fixed: '#1e1b16'
  on-tertiary-fixed-variant: '#4a463f'
  background: '#fff8f6'
  on-background: '#1e1b19'
  surface-variant: '#e9e1de'
typography:
  headline-xl:
    fontFamily: Plus Jakarta Sans
    fontSize: 40px
    fontWeight: '800'
    lineHeight: 48px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 36px
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
  body-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '500'
    lineHeight: 28px
  body-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 16px
    fontWeight: '500'
    lineHeight: 24px
  label-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
    letterSpacing: 0.05em
  label-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 12px
    fontWeight: '700'
    lineHeight: 16px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 12px
  md: 24px
  lg: 40px
  xl: 64px
  margin-mobile: 20px
  margin-desktop: auto
  gutter: 16px
---

## Brand & Style

The design system is crafted to evoke a sense of "refreshing delight." It targets a modern, trend-conscious demographic that appreciates the ritual of a tea break. The brand personality is approachable, playful, and meticulously clean, bridging the gap between a high-end boutique aesthetic and the friendly warmth of a local cafe.

The visual style is **Soft Minimalism**. It leverages the high-whitespace and structured precision of modern design tools (like Figma) but softens the technical edge with a "kawaii" pastel palette and organic, oversized rounded corners. The goal is to make the interface feel as smooth and satisfying as the beverage itself, prioritizing ease of use and visual serenity.

## Colors

The color strategy utilizes a triad of low-saturation pastels to maintain a "clean" feel without sacrificing the brand's cute personality. 

- **Primary (Soft Pink):** Used for call-to-action buttons, active states, and brand-critical highlights. It represents the sweetness and joy of the product.
- **Secondary (Mint Green):** Used for success states, nutritional badges, or "freshness" indicators (e.g., organic leaves, ice levels).
- **Tertiary (Cream):** This is the foundation of the UI. Instead of pure white, this warm off-white creates a softer, more premium "paper" feel that reduces eye strain.
- **Neutral (Warm Charcoal):** Used for typography and iconography. It provides enough contrast for accessibility while avoiding the harshness of pure black.

## Typography

This design system uses **Plus Jakarta Sans** across all levels. It was selected for its modern, geometric construction and inherently friendly, open counters that align with the "clean UI" requirement.

- **Headlines:** Use Bold or ExtraBold weights with slight negative letter-spacing to create a "punchy," modern look typical of high-end tech products.
- **Body:** Use Medium weights (500) rather than Regular (400) to ensure legibility against pastel backgrounds.
- **Labels:** Small labels use a heavier weight and slight tracking to remain distinct even at 12px.

## Layout & Spacing

The layout philosophy is built on a **Fluid Grid** with generous "breathability." 

- **Mobile:** A 4-column grid with 20px side margins. Elements should feel "airy," with vertical spacing between sections (e.g., Featured Drinks vs. Categories) set to a minimum of 40px (lg).
- **Desktop:** The content is constrained to a 1200px max-width container using a 12-column grid.
- **Vertical Rhythm:** An 8px base unit governs all dimensions. Use large padding within cards (24px) to ensure the content doesn't feel cramped, maintaining the minimalist aesthetic.

## Elevation & Depth

To maintain the "clean" and "minimalist" look, this design system avoids heavy shadows. Instead, it uses **Ambient Depth**:

- **Surface Layers:** The background is the Tertiary Cream. Content cards use pure white (#FFFFFF) to subtly pop forward.
- **Shadows:** Use extremely soft, diffused shadows. 
    - *Formula:* `0px 10px 30px rgba(74, 69, 67, 0.05)`. 
    - The shadow color should be a semi-transparent version of the Neutral color, never pure black, to keep the "soft" feel.
- **Active States:** When a user interacts with a card or button, it should slightly scale down (0.98x) rather than increasing shadow depth, mimicking a physical "press" into a soft surface.

## Shapes

The shape language is the core of the system's "cute" appeal. 

- **Standard Elements:** Buttons and input fields use `rounded-lg` (1rem / 16px).
- **Containers:** Product cards and bottom sheets use `rounded-xl` (1.5rem / 24px) or higher to emphasize a friendly, non-threatening geometry.
- **Selection States:** Use pill-shaped (fully rounded) containers for chips and tags to contrast against the softer rectangles of the cards.

## Components

### Buttons
- **Primary:** Soft Pink background with Neutral text. High-contrast enough for legibility but soft in tone. Full-width on mobile for easy thumb reach.
- **Secondary/Ghost:** Mint Green border (2px) with Mint Green text. Use for "Add to Favorites" or "View Details."

### Input Fields
- Backgrounds should be a slightly darker shade of the Cream base or pure white.
- Borders are unnecessary; use subtle shadows or a 1px soft-grey stroke to define the boundary.
- Icons (search, location) should be simplified, thick-stroke line icons.

### Cards
- Product cards (Milk Teas) feature a large image of the drink at the top.
- Content is bottom-aligned with a 24px internal padding.
- Use a "floating" style with the ambient shadow defined in the Elevation section.

### Chips (Toppings/Sugar Levels)
- Use the "Pill" shape. Unselected states are light cream; selected states are the Primary Pink with a subtle bounce animation.

### Progress Indicators
- A custom "Boba" loader: three small circles (pearls) that bounce in a sequence using the Primary Pink color.