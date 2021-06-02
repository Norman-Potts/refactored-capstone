 	
	/* function ConvertNumberPosition
		
		purpose: converts position to a string value of position 
		Ex: 1 is equal to "Lifeguard"
	
		parameters: Position_Number 
		
		return: Position_Word
	*/
	function ConvertNumberPosition( Position_Number )
	{
		
		var Position_Word = "";						
		if(Position_Number == 1)
		{
			Position_Word = "Lifeguard";
		}
		else if (Position_Number == 2)
		{
			Position_Word = "Instructor";
		}
		else if (Position_Number == 3)
		{
			Position_Word = "Headguard";
		}
		else if (Position_Number == 4)
		{
			Position_Word = "Supervisor";
		}
		else
		{
			Position_Word = "ERROR: position is not a correct number";
			/*________________ WRITE TO ERROR LOG! _____________________*/
		}

		return Position_Word;
	}
	
	

	/* function ConvertWordPosition
		
		purpose: converts position word to the number value of position. Does the reverse of ConvertNumberPosition
		Ex:  "Lifeguard" is equal to 1
	
		parameters: Position_Word
		
		return: Position_Number
	*/
	function ConvertWordPosition( Position_Word )
	{
		
		var Position_Number
		
			if( Position_Word == "Lifeguard")
			{
				Position_Number = 1;
			}
			else if (Position_Word == "Instructor")
			{
				Position_Number = 2;
			}
			else if (Position_Word == "Headguard")
			{
				Position_Number = 3;
			}
			else if (Position_Word == "Supervisor")
			{
				Position_Number = 4;
			}
			else
			{
				Position_Number = "ERROR: position is not a correct number";
				/*________________ WRITE TO ERROR LOG! _____________________*/
			}
	
		return Position_Number;
	}