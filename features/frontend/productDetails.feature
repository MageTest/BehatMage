Feature: ProductDetails
  As a Customer using a commerce website
  I want to be able to see Product details
  So that I can find the Product I am looking for

  Scenario Outline: See Product Name
    Given the following products exist:
      | sku   | name           |
      | test1 | Test Product 1 |
      | test2 | Test Product 2 |
    When I am on "<url>"
    Then I should see text "<name>"

    Examples:
      | url                  | name           |
      | /test-product-1.html | Test Product 1 |
      | /test-product-2.html | Test Product 2 |

