Feature: homepage
	As a website visitor 
	I want to see the homepage
	So that I can understand what the website offers and how it can benefit me

	Scenario: Display Logo
	Given I am on "home page"
	Then I should see "Magento Logo"

	Scenario: Display Search Box
	Given I am on "home page"
	Then I should see "a textbox for my search term"
	And the search box should contain the message "Search entire store here"
	And I should see a button with the text "Search" 	

	Scenario: Display Welcome Message
	Given I am on "home page"
	Then I should see "Default welcome msg!"

	Scenario: Display Account Menu
	Given I am on "home page"
	Then I should see "My Account, My Wishlist, My Cart, Checkout, Log In"

	Scenario: Display Lang
	Scenario: Display Main Menu
	Given I am on "home page"
	Then I should see "Furniture, Electronics, Apparel, Music, Ebooks"
	
	
