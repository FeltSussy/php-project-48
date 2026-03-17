### Hexlet tests and linter status:
[![Actions Status](https://github.com/FeltSussy/php-project-48/actions/workflows/hexlet-check.yml/badge.svg)](https://github.com/FeltSussy/php-project-48/actions)
[![Quality Gate Status](https://sonarcloud.io/api/project_badges/measure?project=FeltSussy_php-project-48&metric=alert_status)](https://sonarcloud.io/summary/new_code?id=FeltSussy_php-project-48)
[![Bugs](https://sonarcloud.io/api/project_badges/measure?project=FeltSussy_php-project-48&metric=bugs)](https://sonarcloud.io/summary/new_code?id=FeltSussy_php-project-48)
[![Code Smells](https://sonarcloud.io/api/project_badges/measure?project=FeltSussy_php-project-48&metric=code_smells)](https://sonarcloud.io/summary/new_code?id=FeltSussy_php-project-48)
[![Coverage](https://sonarcloud.io/api/project_badges/measure?project=FeltSussy_php-project-48&metric=coverage)](https://sonarcloud.io/summary/new_code?id=FeltSussy_php-project-48)
[![Duplicated Lines (%)](https://sonarcloud.io/api/project_badges/measure?project=FeltSussy_php-project-48&metric=duplicated_lines_density)](https://sonarcloud.io/summary/new_code?id=FeltSussy_php-project-48)
[![Lines of Code](https://sonarcloud.io/api/project_badges/measure?project=FeltSussy_php-project-48&metric=ncloc)](https://sonarcloud.io/summary/new_code?id=FeltSussy_php-project-48)
[![Reliability Rating](https://sonarcloud.io/api/project_badges/measure?project=FeltSussy_php-project-48&metric=reliability_rating)](https://sonarcloud.io/summary/new_code?id=FeltSussy_php-project-48)
[![Security Rating](https://sonarcloud.io/api/project_badges/measure?project=FeltSussy_php-project-48&metric=security_rating)](https://sonarcloud.io/summary/new_code?id=FeltSussy_php-project-48)
[![Technical Debt](https://sonarcloud.io/api/project_badges/measure?project=FeltSussy_php-project-48&metric=sqale_index)](https://sonarcloud.io/summary/new_code?id=FeltSussy_php-project-48)
[![Maintainability Rating](https://sonarcloud.io/api/project_badges/measure?project=FeltSussy_php-project-48&metric=sqale_rating)](https://sonarcloud.io/summary/new_code?id=FeltSussy_php-project-48)
[![Vulnerabilities](https://sonarcloud.io/api/project_badges/measure?project=FeltSussy_php-project-48&metric=vulnerabilities)](https://sonarcloud.io/summary/new_code?id=FeltSussy_php-project-48)

# GenDiff

GenDiff is a command-line utility for comparing two configuration files (JSON/YAML) and showing the difference between them.

---

## Features

* Supports input formats: JSON, YAML (YML)
* Handles nested structures
* Multiple output formats:

  * stylish (default)
  * plain
  * json

---

## Installation

```bash
git clone https://github.com/FeltSussy/php-project-48.git
cd php-project-48
make install
```

---

## Usage

```bash
gendiff [options] <file1> <file2>
```

Example:

```bash
gendiff file1.json file2.yaml
```

---

## Options

| make lint    | Description                          |
| ------------ | ------------------------------------ |
| -f, --format | Output format (stylish, plain, json) |
| -h, --help | Shows "help" screen |

---

## Examples

### Stylish (default)

[![asciicast](https://asciinema.org/a/cgxMdbOjogowjvLb.svg)](https://asciinema.org/a/cgxMdbOjogowjvLb)

---

### Plain

[![asciicast](https://asciinema.org/a/6vQKXUcr1T4D8TNA.svg)](https://asciinema.org/a/6vQKXUcr1T4D8TNA)

---

### JSON

[![asciicast](https://asciinema.org/a/H9zBTOrezXlCNbev.svg)](https://asciinema.org/a/H9zBTOrezXlCNbev)

---

## Supported formats

* .json
* .yaml
* .yml

