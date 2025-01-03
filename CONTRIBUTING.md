# Welcome-to-Open-Source Contributor Guide 🌟

Thank you for contributing to this open-source project! Follow the steps below to add yourself to the contributors list:

## Prerequisites

You will first need **Git**, which you can download from [here](https://git-scm.com/).

## Step 1: Fork the Repository

1. Go to the [Welcome-to-Open-Source repository](https://github.com/CodeByMoriarty/Your-First-Open-Source-Contribution--Make-It-Count-).
2. Click the **Fork** button at the top-right of the page.

## Step 2: Clone Your Fork

1. After forking, you will land on your forked repository's page.
2. Click the green **Code** button, and copy the repository URL.
3. Open your terminal and type:
   ```bash
   git clone <your-forked-repo-url>
Replace <your-forked-repo-url> with the URL you copied.

Step 3: Edit the README.md File
Open the README.md file located in the Welcome-to-Open-Source folder.

Add your name to the contributors list by inserting the following code snippet inside the <tbody> tag:

html
Copy code
<td align="center">
    <a href="https://github.com/your-username">
        <img src="https://avatars.githubusercontent.com/u/your-avatar-id?v=4" width="100px;" alt="Your Name"/>
        <br />
        <sub><b>Your Name</b></sub>
    </a>
</td>
Replace https://github.com/your-username with your GitHub profile URL.

Replace https://avatars.githubusercontent.com/u/your-avatar-id?v=4 with your GitHub avatar URL.

Replace Your Name with your name.

Step 4: Save and Commit Your Changes
Save your changes to the README.md file.
In the terminal, stage the changes by typing:

git add .
Commit the changes with a message:

git commit -m "Adding my name to the contributors list 🌟"

Step 5: Push Your Changes
Push the changes to your forked repository:

git push origin master

Step 6: Create a Pull Request
Go to your forked repository on GitHub.
Click the green Open Pull Request button.
In the comments section, type: "Adding my name to the contributors list 🌟".
Press Create Pull Request.

Step 7: Wait for Review
Your pull request will be reviewed by a maintainer. Once approved, it will be merged into the main repository, and your name will be added to the contributors list!