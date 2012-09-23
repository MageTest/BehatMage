Feature: Developer can hook events
  As a developer
  In order to setup magento ahead of scenarios
  I should be able to clear the cache

  Background:
    Given we have some files in the config cache of magento

  Scenario: before scenario hook
    When I run any scenario
    Then the before hook will call the cache manager and clear the cache

  Scenario: after scenario hook
    When I run any scenario
    Then the after hook will clean up and configuration and fixtures