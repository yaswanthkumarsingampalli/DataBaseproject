import smtplib
import pymysql

# Function to send email
def send_email(recipient_email, subject, body):
    sender_email = 'singampalliyaswanthkumar@gmail.com'  # Replace with your sender email
    sender_password = 'dmlydipcnharcqgf'  # Replace with your sender email password

    try:
        # Connect to the SMTP server
        smtp_server = 'smtp.gmail.com'
        smtp_port = 587
        server = smtplib.SMTP(smtp_server, smtp_port)
        server.starttls()
        server.login(sender_email, sender_password)

        # Compose the email message
        message = f'Subject: {subject}\n\n{body}'

        # Send the email
        server.sendmail(sender_email, recipient_email, message)
        print('Email sent successfully!')

        # Disconnect from the SMTP server
        server.quit()
    except Exception as e:
        print(f'Error sending email: {str(e)}')

# Connect to MySQL database
def connect_to_database():
    try:
        connection = pymysql.connect(host='localhost',  # Only provide the hostname without port
                                     port=3306,         # Port number
                                     user='root',       # Replace with your MySQL username
                                     password='Msdhoni77@',  # Replace with your MySQL password
                                     database='BloodConnect')  # Replace with your database name
        return connection
    except Exception as e:
        print(f"Error connecting to database: {str(e)}")
        return None

# Execute SQL query and fetch email addresses
def fetch_email_addresses(query):
    connection = connect_to_database()
    if connection:
        try:
            with connection.cursor() as cursor:
                cursor.execute(query)
                email_addresses = cursor.fetchall()
                return [email[0] for email in email_addresses]
        except Exception as e:
            print(f"Error executing SQL query: {str(e)}")
        finally:
            connection.close()

# Main function to send blood donation request emails
def main():
    # SQL queries to fetch email addresses
    exact_match_query = """
        SELECT distinct email_address
        FROM hospitals
        UNION
        SELECT email_address FROM donors WHERE abo_group in ('A+', 'O+', 'O-');
    """

    non_exact_match_query = """
        SELECT distinct email_address
        FROM donors
        WHERE email_address NOT IN (
            SELECT distinct email_address FROM hospitals
            UNION
            SELECT distinct email_address FROM donors WHERE abo_group in ('A+', 'O+', 'O-')
        );
    """

    # Fetch email addresses
    exact_match_emails = fetch_email_addresses(exact_match_query)
    non_exact_match_emails = fetch_email_addresses(non_exact_match_query)

    # Send emails to recipients
    if exact_match_emails:
        for email in exact_match_emails:
            subject = "Blood Donation Request"
            body = "One patient wants blood of your blood group. Here are the patient's details..."
            send_email(email, subject, body)

    if non_exact_match_emails:
        for email in non_exact_match_emails:
            subject = "Blood Donation Request"
            body = "One person needs blood of a specific blood group. Please contact if you or your family members have the same blood group."
            send_email(email, subject, body)

# Execute the main function
if __name__ == "__main__":
    main()
