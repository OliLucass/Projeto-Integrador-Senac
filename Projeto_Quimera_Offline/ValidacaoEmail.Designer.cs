namespace Projeto_integrador
{
    partial class ValidacaoEmail
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
            lbl = new Label();
            txtEmail = new TextBox();
            txtCodigo = new TextBox();
            lblMensagem = new Label();
            label1 = new Label();
            label2 = new Label();
            button1 = new Button();
            button2 = new Button();
            lblCodigo = new Label();
            SuspendLayout();
            // 
            // lbl
            // 
            lbl.Font = new Font("Segoe UI", 14.25F, FontStyle.Regular, GraphicsUnit.Point, 0);
            lbl.ForeColor = Color.FromArgb(234, 234, 234);
            lbl.Location = new Point(247, 129);
            lbl.Name = "lbl";
            lbl.Size = new Size(227, 34);
            lbl.TabIndex = 0;
            lbl.Text = "VERIFICAÇÃO DO E-MIAL";
            // 
            // txtEmail
            // 
            txtEmail.Location = new Point(245, 194);
            txtEmail.Name = "txtEmail";
            txtEmail.Size = new Size(229, 23);
            txtEmail.TabIndex = 1;
            txtEmail.Leave += textBox1_Leave;
            // 
            // txtCodigo
            // 
            txtCodigo.Location = new Point(245, 258);
            txtCodigo.Name = "txtCodigo";
            txtCodigo.Size = new Size(100, 23);
            txtCodigo.TabIndex = 2;
            // 
            // lblMensagem
            // 
            lblMensagem.AutoSize = true;
            lblMensagem.BackColor = Color.FromArgb(10, 15, 28);
            lblMensagem.ForeColor = Color.FromArgb(234, 234, 234);
            lblMensagem.Location = new Point(247, 220);
            lblMensagem.Name = "lblMensagem";
            lblMensagem.Size = new Size(0, 15);
            lblMensagem.TabIndex = 3;
            // 
            // label1
            // 
            label1.AutoSize = true;
            label1.ForeColor = Color.FromArgb(234, 234, 234);
            label1.Location = new Point(245, 176);
            label1.Name = "label1";
            label1.Size = new Size(41, 15);
            label1.TabIndex = 4;
            label1.Text = "EMAIL";
            // 
            // label2
            // 
            label2.AutoSize = true;
            label2.ForeColor = Color.FromArgb(234, 234, 234);
            label2.Location = new Point(247, 240);
            label2.Name = "label2";
            label2.Size = new Size(52, 15);
            label2.TabIndex = 5;
            label2.Text = "CODIGO";
            label2.Click += label2_Click;
            // 
            // button1
            // 
            button1.Location = new Point(480, 194);
            button1.Name = "button1";
            button1.Size = new Size(75, 23);
            button1.TabIndex = 6;
            button1.Text = "ENVIAR";
            button1.UseVisualStyleBackColor = true;
            button1.Click += button1_Click;
            // 
            // button2
            // 
            button2.Location = new Point(363, 258);
            button2.Name = "button2";
            button2.Size = new Size(75, 23);
            button2.TabIndex = 7;
            button2.Text = "VERIFICAR";
            button2.UseVisualStyleBackColor = true;
            button2.Click += button2_Click;
            // 
            // lblCodigo
            // 
            lblCodigo.AutoSize = true;
            lblCodigo.Location = new Point(248, 284);
            lblCodigo.Name = "lblCodigo";
            lblCodigo.Size = new Size(38, 15);
            lblCodigo.TabIndex = 8;
            lblCodigo.Text = "label3";
            // 
            // ValidacaoEmail
            // 
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            BackColor = Color.FromArgb(10, 15, 28);
            ClientSize = new Size(800, 450);
            Controls.Add(lblCodigo);
            Controls.Add(button2);
            Controls.Add(button1);
            Controls.Add(label2);
            Controls.Add(label1);
            Controls.Add(lblMensagem);
            Controls.Add(txtCodigo);
            Controls.Add(txtEmail);
            Controls.Add(lbl);
            Name = "ValidacaoEmail";
            Text = "ValidacaoEmail";
            Load += ValidacaoEmail_Load;
            ResumeLayout(false);
            PerformLayout();
        }

        #endregion

        private Label lbl;
        private TextBox txtEmail;
        private TextBox txtCodigo;
        private Label lblMensagem;
        private Label label1;
        private Label label2;
        private Button button1;
        private Button button2;
        private Label lblCodigo;
    }
}