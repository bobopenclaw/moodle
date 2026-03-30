@mod @mod_assign @javascript
Feature: Control visibility of notify student options in assignment grading
  In order to avoid discrepancies in notifications about released feedback
  As a teacher
  I need notify student controls to respect the assignment admin settings

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
      | student1 | Student   | 1        | student1@example.com |
    And the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1        | 0        | 1         |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |
    And the following config values are set as admin:
      | sendstudentnotifications | 1 | assign |
      | allownotifycontrol       | 1 | assign |
    And the following "activity" exists:
      | activity                            | assign                  |
      | course                              | C1                      |
      | name                                | Test assignment name    |
      | intro                               | Submit your online text |
      | submissiondrafts                    | 0                       |
      | markingworkflow                     | 1                       |
      | assignfeedback_comments_enabled     | 1                       |
      | assignsubmission_onlinetext_enabled | 1                       |
    And the following "mod_assign > submissions" exist:
      | assign                | user     | onlinetext                        |
      | Test assignment name  | student1 | I'm the student first submission  |

  Scenario: Grading panel shows notify student checkbox when notify control is enabled
    Given I am on the "Test assignment name" "assign activity" page logged in as teacher1
    When I click on "Grade actions" "actionmenu" in the "Student 1" "table_row"
    And I choose "Grade" in the open action menu
    Then I should see "Notify student"
    And the field "Notify student" matches value "1"

  Scenario: Grading panel hides notify student checkbox when notify control is disabled
    Given the following config values are set as admin:
      | allownotifycontrol | 0 | assign |
    And I am on the "Test assignment name" "assign activity" page logged in as teacher1
    When I click on "Grade actions" "actionmenu" in the "Student 1" "table_row"
    And I choose "Grade" in the open action menu
    Then "Notify student" "checkbox" should not exist

  Scenario: Marking workflow bulk release shows notify student select when notify control is enabled
    Given I am on the "Test assignment name" "assign activity" page logged in as teacher1
    And I navigate to "Submissions" in current page administration
    When I click on "Select all" "checkbox"
    And I click on "Change marking state" "button" in the "sticky-footer" "region"
    Then I should see "Notify student"
    And I should see "Marking workflow state"

  Scenario: Marking workflow bulk release hides notify student select when notify control is disabled
    Given the following config values are set as admin:
      | allownotifycontrol | 0 | assign |
    And I am on the "Test assignment name" "assign activity" page logged in as teacher1
    And I navigate to "Submissions" in current page administration
    When I click on "Select all" "checkbox"
    And I click on "Change marking state" "button" in the "sticky-footer" "region"
    Then "Notify student" "select" should not exist
    And I should see "Marking workflow state"

  Scenario: Quick grading shows notify students checkbox when notify control is enabled
    Given I am on the "Test assignment name" "assign activity" page logged in as teacher1
    And I navigate to "Submissions" in current page administration
    When I click on "Quick grading" "checkbox"
    Then I should see "Notify students" in the "sticky-footer" "region"

  Scenario: Quick grading hides notify students checkbox when notify control is disabled
    Given the following config values are set as admin:
      | allownotifycontrol | 0 | assign |
    And I am on the "Test assignment name" "assign activity" page logged in as teacher1
    And I navigate to "Submissions" in current page administration
    When I click on "Quick grading" "checkbox"
    Then "Notify students" "checkbox" should not exist in the "sticky-footer" "region"

  Scenario: Released grading in the grading panel can suppress student notification when notify control is enabled
    Given I am on the "Test assignment name" "assign activity" page logged in as teacher1
    When I navigate to "Submissions" in current page administration
    And I click on "Grade actions" "actionmenu" in the "Student 1" "table_row"
    And I choose "Grade" in the open action menu
    And I set the field "Grade out of 100" to "50"
    And I set the field "Marking workflow state" to "Released"
    And I set the field "Notify student" to "0"
    And I press "Save changes"
    Then I should see "Released" in the "Student 1" "table_row"

  Scenario: Released grading in the grading panel uses the default notification setting when notify control is disabled
    Given the following config values are set as admin:
      | allownotifycontrol       | 0 | assign |
      | sendstudentnotifications | 0 | assign |
    And I am on the "Test assignment name" "assign activity" page logged in as teacher1
    When I navigate to "Submissions" in current page administration
    And I click on "Grade actions" "actionmenu" in the "Student 1" "table_row"
    And I choose "Grade" in the open action menu
    Then "Notify student" "checkbox" should not exist
    When I set the field "Grade out of 100" to "50"
    And I set the field "Marking workflow state" to "Released"
    And I press "Save changes"
    Then I should see "Released" in the "Student 1" "table_row"

  Scenario: Released marking workflow bulk action uses release state when notify control is disabled
    Given the following config values are set as admin:
      | allownotifycontrol       | 0 | assign |
      | sendstudentnotifications | 1 | assign |
    And I am on the "Test assignment name" "assign activity" page logged in as teacher1
    And I navigate to "Submissions" in current page administration
    When I click on "Select all" "checkbox"
    And I click on "Change marking state" "button" in the "sticky-footer" "region"
    Then "Notify student" "select" should not exist
    When I set the field "Marking workflow state" to "Released"
    And I press "Save changes"
    Then I should see "Released" in the "Student 1" "table_row"

  Scenario: Quick grading hides notify students checkbox and still saves grades when notify control is disabled
    Given the following config values are set as admin:
      | allownotifycontrol       | 0 | assign |
      | sendstudentnotifications | 1 | assign |
    And I am on the "Test assignment name" "assign activity" page logged in as teacher1
    And I navigate to "Submissions" in current page administration
    When I click on "Quick grading" "checkbox"
    Then "Notify students" "checkbox" should not exist in the "sticky-footer" "region"
    When I set the field "User grade" to "60.0"
    And I click on "Save" "button" in the "sticky-footer" "region"
    Then I should see "The grade changes were saved"
