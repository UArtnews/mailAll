Feature:
  In order to populate publications with articles
  As an editor
  I want to store articles in the article cart for later retrieval.

@javascript
Scenario: Ensure article cart modal opens
  Given I am on "edit/Test"
  And I forceClick the "article-cart-btn" button
  Then "cartList" should be visible

@javascript
Scenario: Add an item to the cart from Test edit home
  Given I am on "edit/Test"
  And I jsClick "articleContent"
  And "addToCartBtn" should be visible
  And I jsClick "addToCartBtn"
  And I forceClick the "article-cart-btn" button
  Then "cartList" should be visible
  And I should see "Remove from cart"