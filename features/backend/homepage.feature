Feature: Index Page
  As a website administrator
  I want to see the dashboard  page
  So that I can understand what the website offers and how it can benefit me

  Background:
    Given there is an admin user "admintest" "admin"

  Scenario: Display Header
    Given I am logged in as admin "admintest" with credentials "admin"
     When I open admin URI "/admin/system_account/"
     Then I should see that the page title is "Dashboard"
