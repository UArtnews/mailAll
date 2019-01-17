Feature: Navigation Tabs
  In order to navigate this webpage
  As an editor
  I need to be able to follow these nav-bar links

Scenario: User clicks on Test
  Given I am on "edit/Test"
  When I follow "navbar-brand-link"
  Then I should be on "edit/Test"

Scenario: User clicks on articles
  Given I am on "edit/Test"
  When I follow "articles-nav-link"
  Then I should be on "edit/Test/articles"

Scenario: User clicks on publications
  Given I am on "edit/Test"
  When I follow "publications-nav-link"
  Then I should be on "edit/Test/publications"

Scenario: User clicks on images
  Given I am on "edit/Test"
  When I follow "images-nav-link"
  Then I should be on "edit/Test/images"

Scenario: User clicks on help
  Given I am on "edit/Test"
  When I follow "help-nav-link"
  Then I should be on "edit/Test/help"