---
name: Nocturnal Velvet
colors:
  surface: '#161121'
  surface-dim: '#161121'
  surface-bright: '#3c3648'
  surface-container-lowest: '#100b1b'
  surface-container-low: '#1e1929'
  surface-container: '#221d2e'
  surface-container-high: '#2d2739'
  surface-container-highest: '#383244'
  on-surface: '#e9def6'
  on-surface-variant: '#cbc3d5'
  inverse-surface: '#e9def6'
  inverse-on-surface: '#332e3f'
  outline: '#948e9f'
  outline-variant: '#494553'
  surface-tint: '#cebdff'
  primary: '#cebdff'
  on-primary: '#390094'
  primary-container: '#9d7bff'
  on-primary-container: '#320085'
  inverse-primary: '#6844c7'
  secondary: '#cdc1e5'
  on-secondary: '#342c49'
  secondary-container: '#4e4463'
  on-secondary-container: '#bfb3d7'
  tertiary: '#d2bcfa'
  on-tertiary: '#38265a'
  tertiary-container: '#9c88c2'
  on-tertiary-container: '#322053'
  error: '#ffb4ab'
  on-error: '#690005'
  error-container: '#93000a'
  on-error-container: '#ffdad6'
  primary-fixed: '#e8ddff'
  primary-fixed-dim: '#cebdff'
  on-primary-fixed: '#21005e'
  on-primary-fixed-variant: '#5028ae'
  secondary-fixed: '#eaddff'
  secondary-fixed-dim: '#cdc1e5'
  on-secondary-fixed: '#1f1732'
  on-secondary-fixed-variant: '#4b4260'
  tertiary-fixed: '#ebddff'
  tertiary-fixed-dim: '#d2bcfa'
  on-tertiary-fixed: '#231043'
  on-tertiary-fixed-variant: '#4f3d72'
  background: '#161121'
  on-background: '#e9def6'
  surface-variant: '#383244'
typography:
  display-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '600'
    lineHeight: 40px
  headline-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  title-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 20px
    fontWeight: '500'
    lineHeight: 28px
  body-lg:
    fontFamily: Be Vietnam Pro
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Be Vietnam Pro
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-md:
    fontFamily: Be Vietnam Pro
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
    letterSpacing: 0.01em
  label-sm:
    fontFamily: Be Vietnam Pro
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
    letterSpacing: 0.05em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 4px
  xs: 4px
  sm: 8px
  md: 16px
  lg: 24px
  xl: 40px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 64px
---

## Brand & Style

The design system embodies a "Midnight Aesthetic"—a blend of modern sophistication and playful charm. It is designed for users who seek a focused, immersive experience that feels both high-tech and approachable. 

The style utilizes **Glassmorphism** and **Minimalism** to create depth within a dark environment. By layering translucent surfaces over deep, tonal backgrounds, the UI achieves a "cute" quality through soft edges and glowing accents without sacrificing professional utility. The emotional response is one of calm, mystery, and premium comfort.

## Colors

The palette is centered on a "Dark Lavender" spectrum. The primary color is a vibrant, luminous lavender that acts as the main interactive signal. 

- **Primary**: A rich, medium-light purple used for active states, primary buttons, and critical accents.
- **Backgrounds**: A deep, near-black violet provides the canvas, ensuring the lavender accents "pop" with a neon-adjacent glow.
- **Surfaces**: Iterations of dark purple with varying saturation to define hierarchy.
- **Accents**: Soft lavender tints are used for subtle borders and secondary text to maintain the "cute" and "soft" aesthetic within a high-contrast dark mode.

## Typography

This design system pairs **Plus Jakarta Sans** for headings with **Be Vietnam Pro** for body text. 

- **Plus Jakarta Sans** provides a modern, slightly rounded geometric feel that reinforces the "cute" and friendly brand personality.
- **Be Vietnam Pro** offers exceptional legibility in dark mode, with open apertures that prevent "clumping" of characters on glowing screens.
- **Scale**: Headlines use tighter letter spacing and heavier weights to command attention, while labels utilize increased tracking for clarity against dark backgrounds.

## Layout & Spacing

The layout utilizes a **fluid grid** system with generous internal padding to create an airy, modern feel.

- **Grid**: A 12-column system for desktop, collapsing to 4 columns on mobile.
- **Spacing Rhythm**: An 8pt linear scale ensures consistency. Use `lg` (24px) for major section spacing and `md` (16px) for element grouping.
- **Safe Areas**: Elements should never touch the edge of the viewport; a minimum margin of 16px is required on mobile to maintain the "contained" boutique look.

## Elevation & Depth

Hierarchy is established through **Tonal Layering** and **Backdrop Blurs**.

1.  **Level 0 (Canvas)**: Deepest purple-black.
2.  **Level 1 (Cards/Surfaces)**: Slightly lighter purple with a 1px inner border of low-opacity lavender to simulate a "glass" edge.
3.  **Level 2 (Overlays/Modals)**: Uses a 70% opacity background with a 20px Gaussian blur, creating a frosted glass effect that lets background colors bleed through.
4.  **Shadows**: Avoid pitch-black shadows. Instead, use "Glow Shadows"—diffused, low-opacity shadows tinted with the primary lavender color (`#9D7BFF`) at 15-20% opacity to suggest the element is emitting light.

## Shapes

The shape language is consistently **Rounded**, contributing to the "cute" and modern aesthetic. 

- **Standard Elements**: Buttons and input fields use a 0.5rem (8px) radius.
- **Containers**: Large cards and modals use 1rem (16px) to 1.5rem (24px) corner radii to soften the visual impact.
- **Interactive States**: When hovered or active, shapes should maintain their radius but can utilize a slight scale-up (1.02x) to feel "squishy" and responsive.

## Components

- **Buttons**: Primary buttons are solid lavender with white or deep-purple text. Secondary buttons use a "Ghost" style with a lavender outline and a subtle fill on hover.
- **Inputs**: Darker than the surface background with a 1px lavender border that glows (box-shadow) when focused.
- **Chips**: Pill-shaped with a semi-transparent lavender background and high-contrast text.
- **Lists**: Separated by subtle, low-opacity lavender dividers (10% opacity) to keep the layout clean.
- **Cards**: Feature a subtle gradient from top-left to bottom-right (Deep Purple to a slightly warmer Violet) to add dimension.
- **Glow Indicators**: Use small, blurred circles of the primary color behind high-priority icons or notification badges to reinforce the "Nocturnal" theme.