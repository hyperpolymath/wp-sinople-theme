;; wp-sinople-theme - Guix Package Definition
;; Run: guix shell -D -f guix.scm

(use-modules (guix packages)
             (guix gexp)
             (guix git-download)
             (guix build-system gnu)
             ((guix licenses) #:prefix license:)
             (gnu packages base))

(define-public wp_sinople_theme
  (package
    (name "wp-sinople-theme")
    (version "0.1.0")
    (source (local-file "." "wp-sinople-theme-checkout"
                        #:recursive? #t
                        #:select? (git-predicate ".")))
    (build-system gnu-build-system)
    (synopsis "Guix channel/infrastructure")
    (description "Guix channel/infrastructure - part of the RSR ecosystem.")
    (home-page "https://github.com/hyperpolymath/wp-sinople-theme")
    (license license:agpl3+)))

;; Return package for guix shell
wp_sinople_theme
