#python3 {userID} {Number of contacts}
#Example python3 19 1000

from faker import Faker
fake = Faker()
import random
import string
from phone_gen import PhoneNumber
import sys

args = sys.argv
UserID = args[1]
num = args[2]
phone_number = PhoneNumber("US")



def random_char(char_num):
       return ''.join(random.choice(string.ascii_letters) for _ in range(char_num))


for i in range(int(num)):
    email = random_char(7)+"@gmail.com"
    str = f"insert into Contacts (FirstName,LastName, Email,Phone,UserID) VALUES ('{fake.name().split()[0]}','{fake.name().split()[1]}','{email}','{phone_number.get_number()}','{UserID}');"
    print(str)