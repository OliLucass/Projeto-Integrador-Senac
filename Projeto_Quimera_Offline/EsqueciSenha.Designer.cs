namespace Projeto_integrador
{
    partial class EsqueciSenha
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
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(EsqueciSenha));
            txtEmail = new TextBox();
            label1 = new Label();
            label2 = new Label();
            txtCodigo = new TextBox();
            label3 = new Label();
            EnviarCodigo = new Button();
            button1 = new Button();
            lblCodigo = new Label();
            lblVerificar = new Label();
            txtSenha = new TextBox();
            txtConfirma = new TextBox();
            label4 = new Label();
            btnConfirma = new Button();
            lblMensagem = new Label();
            verSenha = new CheckBox();
            checkBox1 = new CheckBox();
            SuspendLayout();
            // 
            // txtEmail
            // 
            txtEmail.Location = new Point(102, 132);
            txtEmail.Name = "txtEmail";
            txtEmail.Size = new Size(231, 23);
            txtEmail.TabIndex = 0;
            // 
            // label1
            // 
            label1.Font = new Font("SansSerif", 14.2499981F, FontStyle.Regular, GraphicsUnit.Point, 2);
            label1.ForeColor = Color.FromArgb(234, 234, 234);
            label1.Location = new Point(102, 43);
            label1.Name = "label1";
            label1.Size = new Size(360, 48);
            label1.TabIndex = 1;
            label1.Text = "DIGITE O EMAIL CADASTRADO PARA \r\n        RECUPERAR SUA CONTA\r\n";
            label1.Click += label1_Click;
            // 
            // label2
            // 
            label2.AutoSize = true;
            label2.Font = new Font("Century Gothic", 12F, FontStyle.Regular, GraphicsUnit.Point, 0);
            label2.ForeColor = Color.FromArgb(234, 234, 234);
            label2.Location = new Point(102, 104);
            label2.Name = "label2";
            label2.Size = new Size(59, 21);
            label2.TabIndex = 2;
            label2.Text = "EMAIL";
            // 
            // txtCodigo
            // 
            txtCodigo.Location = new Point(102, 192);
            txtCodigo.Name = "txtCodigo";
            txtCodigo.Size = new Size(100, 23);
            txtCodigo.TabIndex = 3;
            // 
            // label3
            // 
            label3.AutoSize = true;
            label3.Font = new Font("Century Gothic", 12F, FontStyle.Regular, GraphicsUnit.Point, 0);
            label3.ForeColor = Color.FromArgb(234, 234, 234);
            label3.Location = new Point(102, 166);
            label3.Name = "label3";
            label3.Size = new Size(82, 21);
            label3.TabIndex = 4;
            label3.Text = "CODIGO";
            // 
            // EnviarCodigo
            // 
            EnviarCodigo.BackColor = Color.FromArgb(168, 3, 12);
            EnviarCodigo.Font = new Font("Century Gothic", 11.25F, FontStyle.Bold, GraphicsUnit.Point, 0);
            EnviarCodigo.ForeColor = Color.FromArgb(234, 234, 234);
            EnviarCodigo.Location = new Point(339, 129);
            EnviarCodigo.Name = "EnviarCodigo";
            EnviarCodigo.Size = new Size(73, 30);
            EnviarCodigo.TabIndex = 5;
            EnviarCodigo.Text = "ENVIAR";
            EnviarCodigo.UseVisualStyleBackColor = false;
            EnviarCodigo.Click += button1_Click;
            // 
            // button1
            // 
            button1.BackColor = Color.FromArgb(168, 3, 12);
            button1.Font = new Font("Century Gothic", 11.25F, FontStyle.Regular, GraphicsUnit.Point, 0);
            button1.ForeColor = Color.FromArgb(234, 234, 234);
            button1.Location = new Point(208, 189);
            button1.Name = "button1";
            button1.Size = new Size(116, 26);
            button1.TabIndex = 6;
            button1.Text = "VERIFICAR";
            button1.UseVisualStyleBackColor = false;
            button1.Click += button1_Click_2;
            // 
            // lblCodigo
            // 
            lblCodigo.ForeColor = Color.FromArgb(234, 234, 234);
            lblCodigo.Location = new Point(369, 195);
            lblCodigo.Name = "lblCodigo";
            lblCodigo.Size = new Size(300, 15);
            lblCodigo.TabIndex = 7;
            lblCodigo.Text = " ";
            // 
            // lblVerificar
            // 
            lblVerificar.ForeColor = Color.FromArgb(234, 234, 234);
            lblVerificar.Location = new Point(418, 138);
            lblVerificar.Name = "lblVerificar";
            lblVerificar.Size = new Size(251, 15);
            lblVerificar.TabIndex = 8;
            lblVerificar.Text = " ";
            // 
            // txtSenha
            // 
            txtSenha.Location = new Point(102, 191);
            txtSenha.Name = "txtSenha";
            txtSenha.PasswordChar = '*';
            txtSenha.Size = new Size(231, 23);
            txtSenha.TabIndex = 9;
            txtSenha.Visible = false;
            txtSenha.TextChanged += txtSenha_TextChanged;
            // 
            // txtConfirma
            // 
            txtConfirma.Location = new Point(102, 260);
            txtConfirma.Name = "txtConfirma";
            txtConfirma.PasswordChar = '*';
            txtConfirma.Size = new Size(231, 23);
            txtConfirma.TabIndex = 10;
            txtConfirma.Visible = false;
            // 
            // label4
            // 
            label4.AutoSize = true;
            label4.Font = new Font("Century Gothic", 12F, FontStyle.Regular, GraphicsUnit.Point, 0);
            label4.ForeColor = Color.FromArgb(234, 234, 234);
            label4.Location = new Point(102, 234);
            label4.Name = "label4";
            label4.Size = new Size(170, 21);
            label4.TabIndex = 11;
            label4.Text = "CONFIRME A SENHA";
            label4.Visible = false;
            // 
            // btnConfirma
            // 
            btnConfirma.BackColor = Color.FromArgb(168, 3, 12);
            btnConfirma.Font = new Font("Century Gothic", 11.25F, FontStyle.Bold, GraphicsUnit.Point, 0);
            btnConfirma.ForeColor = Color.FromArgb(234, 234, 234);
            btnConfirma.Location = new Point(102, 306);
            btnConfirma.Name = "btnConfirma";
            btnConfirma.Size = new Size(113, 26);
            btnConfirma.TabIndex = 12;
            btnConfirma.Text = "CONFIRMAR";
            btnConfirma.UseVisualStyleBackColor = false;
            btnConfirma.Visible = false;
            btnConfirma.Click += btnConfirma_Click;
            // 
            // lblMensagem
            // 
            lblMensagem.AutoSize = true;
            lblMensagem.ForeColor = Color.FromArgb(234, 234, 234);
            lblMensagem.Location = new Point(314, 229);
            lblMensagem.Name = "lblMensagem";
            lblMensagem.Size = new Size(10, 15);
            lblMensagem.TabIndex = 13;
            lblMensagem.Text = " ";
            // 
            // verSenha
            // 
            verSenha.AutoSize = true;
            verSenha.Location = new Point(339, 196);
            verSenha.Name = "verSenha";
            verSenha.Size = new Size(15, 14);
            verSenha.TabIndex = 14;
            verSenha.UseVisualStyleBackColor = true;
            verSenha.Visible = false;
            verSenha.CheckedChanged += verSenha_CheckedChanged;
            // 
            // checkBox1
            // 
            checkBox1.AutoSize = true;
            checkBox1.Location = new Point(339, 264);
            checkBox1.Name = "checkBox1";
            checkBox1.Size = new Size(15, 14);
            checkBox1.TabIndex = 15;
            checkBox1.UseVisualStyleBackColor = true;
            checkBox1.Visible = false;
            checkBox1.CheckedChanged += checkBox1_CheckedChanged;
            // 
            // EsqueciSenha
            // 
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            BackColor = Color.FromArgb(10, 15, 28);
            ClientSize = new Size(681, 423);
            Controls.Add(checkBox1);
            Controls.Add(verSenha);
            Controls.Add(lblMensagem);
            Controls.Add(btnConfirma);
            Controls.Add(label4);
            Controls.Add(txtConfirma);
            Controls.Add(txtSenha);
            Controls.Add(lblVerificar);
            Controls.Add(lblCodigo);
            Controls.Add(button1);
            Controls.Add(EnviarCodigo);
            Controls.Add(label3);
            Controls.Add(txtCodigo);
            Controls.Add(label2);
            Controls.Add(label1);
            Controls.Add(txtEmail);
            Icon = (Icon)resources.GetObject("$this.Icon");
            Name = "EsqueciSenha";
            StartPosition = FormStartPosition.CenterScreen;
            Text = "EsqueciSenha";
            Load += EsqueciSenha_Load;
            ResumeLayout(false);
            PerformLayout();
        }

        #endregion

        private TextBox txtEmail;
        private Label label1;
        private Label label2;
        private TextBox txtCodigo;
        private Label label3;
        private Button EnviarCodigo;
        private Button button1;
        private Label lblCodigo;
        private Label lblVerificar;
        private TextBox txtSenha;
        private TextBox txtConfirma;
        private Label label4;
        private Button btnConfirma;
        private Label lblMensagem;
        private CheckBox verSenha;
        private CheckBox checkBox1;
    }
}