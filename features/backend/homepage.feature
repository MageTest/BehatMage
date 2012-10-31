Feature: Index Page
  As a website administrator
  I want to see the index page
  So that I can understand what the website offers and how it can benefit me

  Scenario: Display Header
    Given I log in as admin user "admin" identified by "123123pass"
     When I open admin URI "/admin/process/list"
     Then I should see text "Index Management"
