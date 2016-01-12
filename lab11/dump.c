/*
 * SeanmichaelStanley
 * Lab 11
 * cs3380
*/

/*References to function libraies with useful functions for this application*/
#include <stdio.h>
#include <sqlite3.h>
#include <string.h>
#include <stdlib.h>


/*Function prototype*/
char* concat(char *s1, char *s2);


/*Application to collect data from an
 * SQLite table and output the data to a .csv file*/
int main(int argc, char** argv){

	//Declare variables to be used in this application
	sqlite3 *db;
	int rc;
	char tblName[25];	
	char* select_sql;
	sqlite3_stmt *select_stmt = NULL;
	FILE* out;

	
	//Check that the proper number of arguments were entered on the command line
	if(argc != 4){
		fprintf(stderr, "USAGE: %s <database file> <table name> <CSV file>\n", argv[0]);
		return 1;
	}

	//Assign command line arguments to variables to be used in the application
	rc = sqlite3_open(argv[1], &db);
	strcpy(tblName, argv[2]);	
	out = fopen(argv[3],"w");

	//Check that the output file was accessed
	if(out == NULL){
		printf("Unable to open output table");
		return 0;
	}

	//Check that the database was accessed
	if(SQLITE_OK != rc){
		fprintf(stderr, "Can't open database: %s\n", sqlite3_errmsg(db));
		sqlite3_close(db);
		return 1;
	}

	//Create the select statement using the table name entered in the command line arguments
	select_sql = concat("SELECT * FROM ", tblName);	
	select_sql = concat(select_sql, ";");//dont forget the semicolon

	//Prepare the select statement and check that it works properly
	rc = sqlite3_prepare_v2(db, select_sql, -1, &select_stmt, NULL);
	if(SQLITE_OK != rc) {
		fprintf(stderr, "Can't prepare select statment %s (%i): %s\n", select_sql, rc, sqlite3_errmsg(db));
		sqlite3_close(db);
		exit(1);
	}

	// While loop to iterate through the table rows
	while(SQLITE_ROW == (rc = sqlite3_step(select_stmt))) {
		
		int col;

		//For loop to iterate across table columns
		for(col=0; col<sqlite3_column_count(select_stmt); col++) {
	
			//Place a comma between values
			if(col != 0){
				fprintf(out,",");
			}
			//Place single quotes around text values
			if(sqlite3_column_type(select_stmt, col) == SQLITE_TEXT){
				fprintf(out, "'%s'", sqlite3_column_text(select_stmt, col));
			}	
			//No quotes for integer values
			else if(sqlite3_column_type(select_stmt, col) == SQLITE_INTEGER){
                                fprintf(out, "%s", sqlite3_column_text(select_stmt, col));
                        }	
			//All other datatypes result in an error	
			else{
				fprintf(out,"error");
				printf("This program cannot handle a column type included in your database!\nOnly text and integer datatypes are allowed.");
			}
		}
		
		//New line for each row
		fprintf(out,"\n");
	}
	

	//propmt user if there was an issue with the query
	if(SQLITE_DONE != rc) {
		fprintf(stderr, "select statement didn't finish with DONE (%i): %s\n", rc, sqlite3_errmsg(db));
	}

	printf("The contents of your table have been placed in your .csv file.\n");
	//Free malloced memory and close the database and file connections
	fclose(out); 
	free(select_sql);
	sqlite3_close(db);
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
