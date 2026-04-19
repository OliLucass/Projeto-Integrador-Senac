namespace Projeto_integrador
{
    partial class TelaLogin
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(TelaLogin));
            label1 = new Label();
            Login = new Button();
            button2 = new Button();
            textEmail = new TextBox();
            textSenha = new TextBox();
            label2 = new Label();
            label3 = new Label();
            verSenha = new CheckBox();
            esqueciSenha = new Label();
            SuspendLayout();
            // 
            // label1
            // 
            label1.BackColor = Color.FromArgb(10, 15, 28);
            label1.Font = new Font("SansSerif", 20F, FontStyle.Regular, GraphicsUnit.Point, 2);
            label1.ForeColor = Color.Transparent;
            label1.Location = new Point(282, 53);
            label1.Name = "label1";
            label1.Size = new Size(151, 50);
            label1.TabIndex = 0;
            label1.Text = "LOGIN";
            label1.TextAlign = ContentAlignment.MiddleCenter;
            // 
            // Login
            // 
            Login.BackColor = Color.FromArgb(168, 3, 12);
            Login.Font = new Font("Century Gothic", 12F, FontStyle.Bold, GraphicsUnit.Point, 0);
            Login.ForeColor = SystemColors.ButtonHighlight;
            Login.Location = new Point(246, 300);
            Login.Name = "Login";
            Login.Size = new Size(88, 38);
            Login.TabIndex = 1;
            Login.Text = "LOGIN";
            Login.UseVisualStyleBackColor = false;
            Login.Click += button1_Click;
            // 
            // button2
            // 
            button2.BackColor = Color.FromArgb(168, 3, 12);
            button2.Font = new Font("Century Gothic", 12F, FontStyle.Bold, GraphicsUnit.Point, 0);
            button2.ForeColor = SystemColors.ButtonHighlight;
            button2.Location = new Point(359, 300);
            button2.Name = "button2";
            button2.Size = new Size(111, 38);
            button2.TabIndex = 2;
            button2.Text = "CADASTRO";
            button2.UseVisualStyleBackColor = false;
            button2.Click += button2_Click;
            // 
            // textEmail
            // 
            textEmail.Location = new Point(246, 161);
            textEmail.Name = "textEmail";
            textEmail.Size = new Size(224, 23);
            textEmail.TabIndex = 3;
            // 
            // textSenha
            // 
            textSenha.Location = new Point(246, 251);
            textSenha.Name = "textSenha";
            textSenha.PasswordChar = '*';
            textSenha.Size = new Size(224, 23);
            textSenha.TabIndex = 4;
            textSenha.TextChanged += textSenha_TextChanged;
            // 
            // label2
            // 
            label2.AutoSize = true;
            label2.Font = new Font("Century Gothic", 12F, FontStyle.Regular, GraphicsUnit.Point, 0);
            label2.ForeColor = SystemColors.ButtonFace;
            label2.Location = new Point(246, 221);
            label2.Name = "label2";
            label2.Size = new Size(63, 21);
            label2.TabIndex = 5;
            label2.Text = "SENHA";
            // 
            // label3
            // 
            label3.AutoSize = true;
            label3.Font = new Font("Century Gothic", 12F, FontStyle.Regular, GraphicsUnit.Point, 0);
            label3.ForeColor = SystemColors.ButtonFace;
            label3.Location = new Point(246, 131);
            label3.Name = "label3";
            label3.Size = new Size(111, 21);
            label3.TabIndex = 6;
            label3.Text = "Email ou User";
            // 
            // verSenha
            // 
            verSenha.AutoSize = true;
            verSenha.Location = new Point(476, 255);
            verSenha.Name = "verSenha";
            verSenha.Size = new Size(15, 14);
            verSenha.TabIndex = 9;
            verSenha.UseVisualStyleBackColor = true;
            verSenha.CheckedChanged += checkBox1_CheckedChanged;
            // 
            // esqueciSenha
            // 
            esqueciSenha.AutoSize = true;
            esqueciSenha.Cursor = Cursors.Hand;
            esqueciSenha.Font = new Font("Segoe UI", 9F, FontStyle.Underline, GraphicsUnit.Point, 0);
            esqueciSenha.ForeColor = SystemColors.HotTrack;
            esqueciSenha.Location = new Point(282, 352);
            esqueciSenha.Name = "esqueciSenha";
            esqueciSenha.Size = new Size(137, 15);
            esqueciSenha.TabIndex = 10;
            esqueciSenha.Text = "ESQUECI MINHA SENHA";
            esqueciSenha.Visible = false;
            esqueciSenha.Click += esqueciSenha_Click;
            // 
            // TelaLogin
            // 
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            BackColor = Color.FromArgb(10, 15, 28);
            ClientSize = new Size(733, 551);
            Controls.Add(esqueciSenha);
            Controls.Add(verSenha);
            Controls.Add(label3);
            Controls.Add(label2);
            Controls.Add(textSenha);
            Controls.Add(textEmail);
            Controls.Add(button2);
            Controls.Add(Login);
            Controls.Add(label1);
            Icon = (Icon)resources.GetObject("$this.Icon");
            Name = "TelaLogin";
            StartPosition = FormStartPosition.CenterScreen;
            Text = "TelaLogin";
            Load += TelaLogin_Load;
            ResumeLayout(false);
            PerformLayout();
        }

        #endregion

        private Label label1;
        private Button Login;
        private Button button2;
        private TextBox textEmail;
        private TextBox textSenha;
        private Label label2;
        private Label label3;
        private CheckBox verSenha;
        private Label esqueciSenha;
    }
}