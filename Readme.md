## Steps To Create a Form Mode.

1. Add the module to modules/custom directory.
2. Create a content type "Agency" with machine name as 'agency'.
3. Add fields to it, remember to add 'field_mail' field to it.
4. Create a Form Mode by the name "Company Form Mode" and machine name as "company_form_mode".
5. Under "Manage Form Display Settings" for agency content type , select "Company Form Mode" as a separate for mode.
6. Create a content of type "Agency" and add Drupal user Acount's email address to it. 
7. Install the module and place the block "Manage Company Profile", wherever you think it should be placed.
8. Visit the page and notice that now you are able to access Company Form Mode.
