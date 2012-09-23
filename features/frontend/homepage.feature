Feature: Home Page
  As a website visitor
  I want to see the homepage
  So that I can understand what the website offers and how it can benefit me

  # Scenario: Display Logo
  #   Given I am on "home page"
  #   Then I should see "Magento Logo"
  # 
  # Scenario: Display Search Box
  #   Given I am on "home page"
  #   Then I should see "a textbox for my search term"
  #   And the search box should contain the message "Search entire store here"
  #   And I should see a button with the text "Search"

  # Scenario: Display Welcome Message
  # Given I am on "home page"
  # Then I should see "Default welcome msg!"
  # 
  # Scenario: Display Account Menu
  # Given I am on "home page"
  # Then I should see "My Account, My Wishlist, My Cart, Checkout, Log In"
  # 
  # Scenario: Display Language
  # Given I am on "home page"
  # Then I should see the text "Your Language"
  # And a dropdown with the options "Czech (Czech Republic), Danish (Denmark), Dutch (Netherlands), English, French, German, Norwegian Bokmai (Norway), Polish (Poland)" 
  # 
  # Scenario: Display Main Menu
  # Given I am on "home page"
  # Then I should see "Furniture, Electronics, Apparel, Music, Ebooks"
  # 
  # Scenario: Display Left Column
  # Given I am on "home page"
  # Then I should see "A Small Banner Advert"
  # And I should see "A Product Feature"
  # And I should see "Popular Tags"
  # 
  # Scenario: Display Main Content
  # Given I am on "home page"
  # Then I should see "A Large Banner Advert"
  # And I should see "A Wide Banner Advert"
  # And I should see "A Best Selling Product Feature showing 6 products"
  # 
  # Scenario: Display Right Column
  # Given I am on "home page"
  # Then I should see "A Product Comparison Feature"
  # And I should see "A Mini View of My Cart"
  # And I should see "A Small Banner Advert"
  # And I should see "A Community Poll"
  # And I should see "A PayPal Advert"
  # 
  # Scenario: Display Footer
  # Given I am on "home page"
  # Then I should see "Select Store" and a dropdown with the values "Blue SKin, Main Store, Modern Theme"
  # And I should see "About Us, Customer Service, privacy Policy, Site Map, Search Terms, Advanced Search, Orders and Returns, Contact Us"
  # And I should see "Help us to Keep Magento Healthy - Report all bugs (ver.1.7.0.2)"
  # And I should see "© 2012 Magento Demo Store. All Rights Reserved."   
 

  Scenario: Contact Us link is shown when active
  Given I set config value for "contacts/contacts/enabled" to "1" in "default" scope
  And the cache is clean
  When I am on "/"
  Then I should see text "Contact Us"


  Scenario: Contact Us link is hidden when disabled
  Given I set config value for "contacts/contacts/enabled" to "0" in "default" scope
  And the cache is clean
  When I am on "/"
  Then I should not see text "Contact Us"

  Scenario: Display Welcome Message
  Given I am on "/"
  Then I should see text "Default welcome msg! "
