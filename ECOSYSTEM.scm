;; SPDX-License-Identifier: AGPL-3.0-or-later
;; SPDX-FileCopyrightText: 2025 Jonathan D.A. Jewell
;; ECOSYSTEM.scm — wp-sinople-theme

(ecosystem
  (version "1.0.0")
  (name "wp-sinople-theme")
  (type "project")
  (purpose "A modern, semantically-aware WordPress theme powered by *ReScript*, *Deno*, and *WASM*. Sinople (from the heraldic term for green) combines traditional WordPress theming with cutting-edge semantic web...")

  (position-in-ecosystem
    "Part of hyperpolymath ecosystem. Follows RSR guidelines.")

  (related-projects
    (project (name "rhodium-standard-repositories")
             (url "https://github.com/hyperpolymath/rhodium-standard-repositories")
             (relationship "standard")))

  (what-this-is "A modern, semantically-aware WordPress theme powered by *ReScript*, *Deno*, and *WASM*. Sinople (from the heraldic term for green) combines traditional WordPress theming with cutting-edge semantic web...")
  (what-this-is-not "- NOT exempt from RSR compliance"))
