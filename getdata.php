<?php
	$servername = "localhost"; 
    $username = "root";
    $password = "";        
    $dbname = "databaseesp";
	
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	if ($conn->connect_error) 
	{
        die("Không thể kết nối tới Database" . $conn->connect_error);
    }
	else
	{	
        $fingerID = $_POST['FingerID'];

        $sql = "SELECT * FROM fingerprint_register WHERE ID_FP=?";
        $result = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($result, $sql)) {
            echo "SQL_Error_Select_card";
            exit();
        }
        else
        {
            mysqli_stmt_bind_param($result, "s", $fingerID);
            mysqli_stmt_execute($result);
            $result1 = mysqli_stmt_get_result($result);
        if ($row = mysqli_fetch_assoc($result1))
        {
            if ($row['Name'] != "Name")
            {
                $name = $row['Name'];
                $sql = "SELECT * FROM fingerprint_register WHERE ID_FP=? AND Name=?";
                $result1 = mysqli_stmt_init($conn);
                if (!mysqli_stmt_prepare($result1, $sql)) {
                    echo "SQL_Error_Select_logs";
                    exit();
                }
                else
                {
                    mysqli_stmt_bind_param($result1, "ss", $fingerID, $name);
                    mysqli_stmt_execute($result1);

                    echo "$fingerID/$name";
                    exit();
                        
                }
                    //*****************************************************
                    //Logout
                
            }
        }
    }
}
							
	$conn->close();  
?>