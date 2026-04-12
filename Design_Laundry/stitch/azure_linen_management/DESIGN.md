# Design System Document: High-End Internal Operations

## 1. Overview & Creative North Star: "The Pristine Curator"
The objective of this design system is to transform a utilitarian laundry management tool into a high-end editorial experience. We are moving away from the "industrial dashboard" aesthetic and toward **"The Pristine Curator."** 

This North Star emphasizes cleanliness, fluid movement, and organized luxury. We achieve this through **Organic Asymmetry**: while the data is dense, the container layouts utilize varying "xl" and "lg" radii to mimic the softness of folded linens. By utilizing expansive white space and tonal depth rather than rigid lines, we create a workspace that feels breathable, professional, and calm.

---

### 2. Colors & Surface Architecture
We do not use borders to define space. We use light and depth.

#### The "No-Line" Rule
**Explicit Instruction:** 1px solid borders for sectioning are strictly prohibited. Boundaries must be defined solely through background color shifts.
*   **Background:** Use `surface` (#faf9fd) for the base canvas.
*   **Sectioning:** Use `surface-container-low` (#f4f3f7) to block out secondary sidebars or secondary content areas.

#### Surface Hierarchy & Nesting
Treat the UI as a series of stacked physical layers. 
*   **Primary Work Surface:** Use `surface-container-lowest` (#ffffff) for the main data cards to make them "pop" against the off-white background.
*   **Nesting:** If a card contains a sub-section (e.g., a customer’s order history within a profile), use `surface-container-high` (#e9e7eb) for that inner container to create a "recessed" look.

#### The "Glass & Gradient" Rule
To elevate the primary brand colors:
*   **CTAs:** Use a subtle linear gradient on primary buttons, transitioning from `primary` (#000a1e) to `primary-container` (#002147) at a 135-degree angle.
*   **Floating Navigation:** Apply `backdrop-blur: 20px` with a semi-transparent `surface-container-lowest` (alpha 80%) for top navigation bars to create a high-end "frosted glass" feel.

---

### 3. Typography: Editorial Utility
We use **Inter** for its mathematical precision and readability in high-density environments.

*   **Display (Large/Med):** Used for daily revenue totals or "Hero" statistics. These should be set with -0.02em letter spacing to feel "tight" and authoritative.
*   **Headline (Sm/Med):** Use for section headers. Always pair these with a `secondary` (#3b6751) color treatment to subtly inject the brand identity into the hierarchy.
*   **Body (Md/Lg):** The workhorse for data. Use `on-surface-variant` (#44474e) for secondary body text to reduce eye strain in high-density tables.
*   **Label (Sm/Md):** All-caps with +0.05em tracking for currency labels (`₱`) and status indicators to ensure they are distinguishable from interactive text.

---

### 4. Elevation & Depth: Tonal Layering
Traditional drop shadows are too "heavy" for a system based on cleanliness.

*   **The Layering Principle:** Place a `surface-container-lowest` card on a `surface-container-low` background. The subtle shift from #ffffff to #f4f3f7 provides all the separation needed.
*   **Ambient Shadows:** For floating Modals or Tooltips, use a shadow with a 40px blur, 0px spread, and 4% opacity of the `primary` color. This creates a natural, atmospheric lift.
*   **The "Ghost Border" Fallback:** If accessibility requires a border (e.g., in high-contrast mode), use the `outline-variant` (#c4c6cf) at **10% opacity**. It should be felt, not seen.

---

### 5. Components & Data Density

#### High-Density Data Tables
*   **Structure:** No vertical or horizontal lines. 
*   **Separation:** Use a 4px vertical gap between rows. Each row should be its own rounded container (`sm` radius) that shifts to `surface-container-highest` (#e3e2e6) on hover.
*   **Currency:** The `₱` symbol should be styled in `tertiary` (#705d00) to ensure financial data is immediately scannable but elegant.

#### Buttons & Interaction
*   **Primary CTA:** `primary` background, `on-primary` text. Radius: `full`.
*   **Secondary/Soft Gold:** Use `tertiary-container` (#c9a900) for "Add New" or "Create" actions. It provides a "Golden Glow" without the vibration of pure yellow.
*   **Status Badges:** Use `lg` (2rem) rounded corners. 
    *   *Paid:* `secondary-container` text on `on-secondary-fixed-variant`.
    *   *Pending:* `tertiary-fixed` background with `on-tertiary-fixed` text.
    *   *Unpaid:* `error-container` background with `on-error-container` text.

#### Input Fields
*   **Style:** Minimalist. Use `surface-container-highest` as the fill color with no border. 
*   **Focus State:** Transition to a 2px "Ghost Border" using `primary-fixed` (#d6e3ff).

#### Iconography
*   **The Soap Bubble Motif:** Use custom laundry icons with "broken lines" and rounded terminals. 
*   **Placement:** Icons should always be accompanied by labels in `label-md` to ensure the "management" aspect of the system remains professional.

---

### 6. Do’s and Don’ts

#### Do
*   **Do** use the `xl` (3rem) radius for large dashboard cards to emphasize the "Lounge" feel.
*   **Do** use "surface-tint" overlays for modal backdrops instead of pure black to keep the navy brand essence alive.
*   **Do** utilize white space as a functional tool to group related data points.

#### Don’t
*   **Don't** use 1px dividers between list items; use 8px–12px of vertical padding instead.
*   **Don't** use pure #000000 for text. Use `on-surface` (#1a1b1e) to maintain a premium, ink-like quality.
*   **Don't** use sharp corners. Every interactive element must have at least a `sm` (0.5rem) radius to stay consistent with the "clean/soft" brand pillars.