/*
 * Seanmichael Stanley
 * Lab11
 * cs3380
*/

//Function libraries
#include <stdio.h>
#include <sqlite3.h>
#include <string.h>
#include <stdlib.h>

/*Function prototype*/
char* concat(char *s1, char *s2);

//Application to load data from a csv file into an SQLite table
int main(int argc, char** argv)
{
	//Declare variables for this function
	sqlite3 *db = NULL;
	int rc;
	char tblName[25];
	char* insert_sql;
	sqlite3_stmt *insert_stmt = NULL;
	FILE* in;
	
	//Check that the proper number of arguments were entered on the command line
	if(argc != 4){
		fprintf(stderr, "USAGE: %s <database file> <table name> <CSV file>\n", argv[0]);
		return 1;
	}

	//Assign command line arguments to variables to be used in the application
	rc = sqlite3_open(argv[1], &db);
	strcpy(tblName, argv[2]);	
	in = fopen(argv[3],"r");

	//Check that the output file was accessed
	if(in == NULL){
		printf("Unable to open output table");
		return 0;
	}

	//Check that the database was accessed
	if(SQLITE_OK != rc){
		fprintf(stderr, "Can't open database: %s\n", sqlite3_errmsg(db));
		sqlite3_close(db);
		return 1;
	}

	//Prepare the statement to delete the contents of the database table
	insert_sql = concat("DELETE FROM ", tblName);
	insert_sql = concat(insert_sql, ";");

	rc = sqlite3_prepare_v2(db, insert_sql, -1, &insert_stmt, NULL);
	if(SQLITE_OK != rc) {
		fprintf(stderr, "Can't prepare DELETE statment %s (%i): %s\n", insert_sql, rc, sqlite3_errmsg(db));
		sqlite3_close(db);
		exit(1);
	}

	//Drop and recreate the database table
	rc = sqlite3_step(insert_stmt);
	if(SQLITE_DONE != rc) {
                fprintf(stderr, "Error while executing DELETE statment %s (%i): %s\n", insert_sql, rc, sqlite3_errmsg(db));
                sqlite3_close(db);
                exit(1);
        }
	
	//declaer the variable to handle each line of the csv file
	char line[1024];
	
	//Create and run insert statements for each line of the csv file
    	while (fgets(line, 1024, in)){
		//Create the insert statements to load data to database
		insert_sql = concat("INSERT INTO ", tblName);
        	insert_sql = concat(insert_sql, " VALUES (");
		insert_sql = concat(insert_sql, line);
		insert_sql = concat(insert_sql, ");");

		//Prepare the insert statement
        	rc = sqlite3_prepare_v2(db, insert_sql, -1, &insert_stmt, NULL);
        	if(SQLITE_OK != rc) {
                	fprintf(stderr, "Can't prepare INSERT statment %s (%i): %s\n", insert_sql, rc, sqlite3_errmsg(db));
                	sqlite3_close(db);
                	exit(1);
        	}

		//Insert a new record into the table
        	rc = sqlite3_step(insert_stmt);
       		if(SQLITE_DONE != rc) {
                	fprintf(stderr, "Error while executing INSERT statment %s (%i): %s\n", insert_sql, rc, sqlite3_errmsg(db));
                	sqlite3_close(db);
                	exit(1);
        	}

		//Clear the insert statement
        	sqlite3_finalize(insert_stmt);
	}	

	//free memory and close the connection to the database and .csv file
	printf("The contents of your .csv file have been uploaded to your table\n");
	fclose(in);
	sqlite3_close(db);
	free(insert_sql);
	return 0;
}

//Function to concatinate strings
char* concat(char *s1, char *s2)
{
	char *result = malloc(strlen(s1)+strlen(s2)+1);
	strcpy(result, s1);
	strcat(result, s2);
	return (result);
}
