# Sinople Theme Roadmap

> **🚧 Major Update in Progress**: Version 1.1.0 introduces the **Theme Transpilation System**, a revolutionary approach to automatically extract, analyze, and recreate WordPress themes using schema-based transpilation and machine learning. See [THEME_TRANSPILATION_ARCHITECTURE.md](THEME_TRANSPILATION_ARCHITECTURE.md) for details.

## Version 1.0.0 (Current)

- [x] Core WordPress theme structure
- [x] Rust WASM semantic processor
- [x] ReScript bindings
- [x] Custom post types (Constructs, Entanglements)
- [x] RDF/Turtle ontology support
- [x] IndieWeb Webmention endpoint
- [x] IndieWeb Micropub endpoint
- [x] WCAG 2.3 AAA compliance
- [x] Semantic graph REST API

## Version 1.1.0 (Planned - Theme Transpilation System) 🚧

### Automated Theme Generation & Extraction

- [x] **Architecture document** - Complete system design (THEME_TRANSPILATION_ARCHITECTURE.md)
- [x] **Web scraper** - License detection, CSS/HTML extraction
- [x] **WordPress themes catalog** - 18 official themes (2003-present)
- [x] **IndieWeb themes catalog** - 8 IndieWeb-compatible themes
- [ ] **Cue/Nix schema definitions** - Structured theme representation
- [ ] **Haskell transpiler** - Schema → ReScript code generation
- [ ] **Feedback collection system** - JSON/YAML/Web UI/API
- [ ] **ML training pipeline** - LSM + Julia/Logtalk for learning

### Theme Library

- [ ] **WordPress Official Themes** - Recreate all 18 themes with modern stack
  - [ ] Twenty Ten through Twenty Twenty-Four
  - [ ] Block themes (2022+) conversion
  - [ ] Classic themes (2010-2021) conversion
- [ ] **IndieWeb Themes** - Port popular IndieWeb themes
  - [ ] Independent Publisher 1 & 2
  - [ ] SemPress
  - [ ] Autonomie
  - [ ] Doublespace

### Self-Learning System

- [ ] **Pattern recognition** - Liquid State Machine for DOM structure
- [ ] **Validation rules** - Logtalk logic programming for correctness
- [ ] **Code generation** - Supervised learning (Julia) for ReScript output
- [ ] **Continuous improvement** - Feedback loop for extraction quality
- [ ] **WCAG AAA enforcement** - Automatic accessibility fixes

### Original Features (v1.1)

- [ ] Deno + Fresh full integration
- [ ] Interactive graph visualization UI
- [ ] Character relationship explorer
- [ ] Inline gloss annotations (hover/click)
- [ ] Advanced SPARQL-like queries
- [ ] Faceted search for constructs
- [ ] Export to various RDF formats (JSON-LD, N-Triples)

## Version 1.2.0 (Future)

- [ ] Real-time collaborative editing
- [ ] Version control for ontologies
- [ ] Import from external ontologies (DBpedia, Wikidata)
- [ ] Machine learning for relationship suggestions
- [ ] Natural language query interface
- [ ] Mobile app (React Native + WASM)

## Version 2.0.0 (Vision)

- [ ] Distributed knowledge graph (IPFS/Solid)
- [ ] Federated semantic networks
- [ ] AI-powered construct generation
- [ ] VR/AR visualization
- [ ] Blockchain-based provenance
- [ ] Multi-language ontologies

## Ongoing

- Security audits
- Performance optimization
- Accessibility improvements
- Community feedback integration
- Documentation updates
