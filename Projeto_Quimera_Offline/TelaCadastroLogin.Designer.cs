namespace Projeto_integrador
{
    partial class TelaCadastroLogin
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
            Button cad;
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(TelaCadastroLogin));
            lb1 = new Label();
            email1 = new Label();
            tipo_user1 = new Label();
            nome1 = new Label();
            nome_user1 = new Label();
            senha1 = new Label();
            cpf1 = new Label();
            label9 = new Label();
            email = new TextBox();
            nome = new TextBox();
            nome_user = new TextBox();
            cpf = new TextBox();
            openFileDialog1 = new OpenFileDialog();
            senha = new TextBox();
            confsenha = new TextBox();
            label1 = new Label();
            tipo_user = new ComboBox();
            lblMensagem = new Label();
            cad = new Button();
            SuspendLayout();
            // 
            // cad
            // 
            cad.BackColor = Color.FromArgb(168, 3, 12);
            cad.Font = new Font("Century Gothic", 9.75F, FontStyle.Bold, GraphicsUnit.Point, 0);
            cad.ForeColor = Color.FromArgb(234, 234, 234);
            cad.Location = new Point(328, 365);
            cad.Name = "cad";
            cad.Size = new Size(116, 40);
            cad.TabIndex = 21;
            cad.Text = "Cadastrar";
            cad.UseVisualStyleBackColor = false;
            cad.Click += button1_Click;
            // 
            // url_foto
            //
            /*
            url_foto.BackColor = Color.FromArgb(168, 3, 12);
            url_foto.Font = new Font("Century Gothic", 9.75F, FontStyle.Bold, GraphicsUnit.Point, 0);
            url_foto.ForeColor = Color.FromArgb(234, 234, 234);
            url_foto.Location = new Point(289, 223);
            url_foto.Name = "url_foto";
            url_foto.Size = new Size(69, 25);
            url_foto.TabIndex = 0;
            url_foto.Text = "Escolher";
            url_foto.UseVisualStyleBackColor = false;
            url_foto.Click += url_foto_Click;
            */
            // 
            // lb1
            // 
            lb1.AutoSize = true;
            lb1.Font = new Font("SansSerif", 27.7499962F, FontStyle.Bold, GraphicsUnit.Point, 2);
            lb1.ForeColor = Color.FromArgb(234, 234, 234);
            lb1.Location = new Point(308, 14);
            lb1.Name = "lb1";
            lb1.Size = new Size(170, 43);
            lb1.TabIndex = 1;
            lb1.Text = "Quimera";
            // 
            // email1
            // 
            email1.AutoSize = true;
            email1.Font = new Font("Century Gothic", 9.75F, FontStyle.Regular, GraphicsUnit.Point, 0);
            email1.ForeColor = Color.FromArgb(234, 234, 234);
            email1.Location = new Point(78, 113);
            email1.Name = "email1";
            email1.Size = new Size(43, 17);
            email1.TabIndex = 2;
            email1.Text = "Email";
            // 
            // tipo_user1
            // 
            tipo_user1.AutoSize = true;
            tipo_user1.Font = new Font("Century Gothic", 9.75F);
            tipo_user1.ForeColor = Color.FromArgb(234, 234, 234);
            tipo_user1.Location = new Point(78, 313);
            tipo_user1.Name = "tipo_user1";
            tipo_user1.Size = new Size(105, 17);
            tipo_user1.TabIndex = 3;
            tipo_user1.Text = "Tipo de usuário";
            // 
            // nome1
            // 
            nome1.AutoSize = true;
            nome1.Font = new Font("Century Gothic", 9.75F, FontStyle.Regular, GraphicsUnit.Point, 0);
            nome1.ForeColor = Color.FromArgb(234, 234, 234);
            nome1.Location = new Point(305, 113);
            nome1.Name = "nome1";
            nome1.Size = new Size(72, 17);
            nome1.TabIndex = 4;
            nome1.Text = "Seu nome";
            // 
            // nome_user1
            // 
            nome_user1.AutoSize = true;
            nome_user1.Font = new Font("Century Gothic", 9.75F);
            nome_user1.ForeColor = Color.FromArgb(234, 234, 234);
            nome_user1.Location = new Point(549, 113);
            nome_user1.Name = "nome_user1";
            nome_user1.Size = new Size(58, 17);
            nome_user1.TabIndex = 5;
            nome_user1.Text = "Apelido";
            // 
            // senha1
            // 
            senha1.AutoSize = true;
            senha1.Font = new Font("Century Gothic", 9.75F);
            senha1.ForeColor = Color.FromArgb(234, 234, 234);
            senha1.Location = new Point(308, 213);
            senha1.Name = "senha1";
            senha1.Size = new Size(47, 17);
            senha1.TabIndex = 6;
            senha1.Text = "Senha";
            // 
            // cpf1
            // 
            cpf1.AutoSize = true;
            cpf1.Font = new Font("Century Gothic", 9.75F);
            cpf1.ForeColor = Color.FromArgb(234, 234, 234);
            cpf1.Location = new Point(78, 222);
            cpf1.Name = "cpf1";
            cpf1.Size = new Size(33, 17);
            cpf1.TabIndex = 8;
            cpf1.Text = "CPF";
            cpf1.Click += label8_Click;
            // 
            // label9
            // 
            label9.AutoSize = true;
            label9.Font = new Font("Century Gothic", 14.25F, FontStyle.Bold, GraphicsUnit.Point, 0);
            label9.ForeColor = Color.FromArgb(234, 234, 234);
            label9.Location = new Point(328, 57);
            label9.Name = "label9";
            label9.Size = new Size(129, 23);
            label9.TabIndex = 10;
            label9.Text = "Cadastre-se:";
            // 
            // email
            // 
            email.Font = new Font("Century Gothic", 9F, FontStyle.Regular, GraphicsUnit.Point, 0);
            email.Location = new Point(78, 141);
            email.Name = "email";
            email.Size = new Size(192, 22);
            email.TabIndex = 12;
            email.TextChanged += email_TextChanged;
            email.Leave += email_Leave;
            // 
            // nome
            // 
            nome.Font = new Font("Century Gothic", 9F, FontStyle.Regular, GraphicsUnit.Point, 0);
            nome.Location = new Point(305, 141);
            nome.Name = "nome";
            nome.Size = new Size(197, 22);
            nome.TabIndex = 14;
            // 
            // nome_user
            // 
            nome_user.Font = new Font("Century Gothic", 9F, FontStyle.Regular, GraphicsUnit.Point, 0);
            nome_user.Location = new Point(549, 141);
            nome_user.Name = "nome_user";
            nome_user.Size = new Size(199, 22);
            nome_user.TabIndex = 15;
            // 
            // cpf
            // 
            cpf.Font = new Font("Century Gothic", 9F, FontStyle.Regular, GraphicsUnit.Point, 0);
            cpf.Location = new Point(78, 248);
            cpf.Name = "cpf";
            cpf.Size = new Size(183, 22);
            cpf.TabIndex = 16;
            cpf.TextChanged += cpf_TextChanged;
            cpf.KeyPress += cpf_KeyPress;
            // 
            // openFileDialog1
            // 
            openFileDialog1.FileName = "openFileDialog1";
            // 
            // senha
            // 
            senha.Font = new Font("Century Gothic", 9F, FontStyle.Regular, GraphicsUnit.Point, 0);
            senha.Location = new Point(308, 248);
            senha.Name = "senha";
            senha.Size = new Size(168, 22);
            senha.TabIndex = 17;
            // 
            // confsenha
            // 
            confsenha.Font = new Font("Century Gothic", 9F, FontStyle.Regular, GraphicsUnit.Point, 0);
            confsenha.Location = new Point(522, 248);
            confsenha.Name = "confsenha";
            confsenha.Size = new Size(174, 22);
            confsenha.TabIndex = 18;
            // 
            // label1
            // 
            label1.AutoSize = true;
            label1.Font = new Font("Century Gothic", 9.75F);
            label1.ForeColor = Color.FromArgb(234, 234, 234);
            label1.Location = new Point(522, 213);
            label1.Name = "label1";
            label1.Size = new Size(115, 17);
            label1.TabIndex = 19;
            label1.Text = "Confirmar senha";
            // 
            // tipo_user
            // 
            tipo_user.Font = new Font("Century Gothic", 9F, FontStyle.Regular, GraphicsUnit.Point, 0);
            tipo_user.FormattingEnabled = true;
            tipo_user.Items.AddRange(new object[] { "comum", "adm" });
            tipo_user.Location = new Point(78, 339);
            tipo_user.Name = "tipo_user";
            tipo_user.Size = new Size(121, 25);
            tipo_user.TabIndex = 22;
            tipo_user.SelectedIndexChanged += tipo_user_SelectedIndexChanged;
            // 
            // lblMensagem
            // 
            lblMensagem.AutoSize = true;
            lblMensagem.Location = new Point(78, 167);
            lblMensagem.Name = "lblMensagem";
            lblMensagem.Size = new Size(10, 15);
            lblMensagem.TabIndex = 23;
            lblMensagem.Text = " ";
            // 
            // TelaCadastroLogin
            // 
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            BackColor = Color.FromArgb(10, 15, 28);
            ClientSize = new Size(800, 450);
            Controls.Add(lblMensagem);
            Controls.Add(tipo_user);
            Controls.Add(cad);
            Controls.Add(label1);
            Controls.Add(confsenha);
            Controls.Add(senha);
            Controls.Add(cpf);
            Controls.Add(nome_user);
            Controls.Add(nome);
            Controls.Add(email);
            Controls.Add(label9);
            Controls.Add(cpf1);
            Controls.Add(senha1);
            Controls.Add(nome_user1);
            Controls.Add(nome1);
            Controls.Add(tipo_user1);
            Controls.Add(email1);
            Controls.Add(lb1);
            Icon = (Icon)resources.GetObject("$this.Icon");
            Name = "TelaCadastroLogin";
            StartPosition = FormStartPosition.CenterScreen;
            Text = "TelaCadastroLogin";
            Load += TelaCadastroLogin_Load;
            ResumeLayout(false);
            PerformLayout();
        }

        #endregion
        private Label lb1;
        private Label email1;
        private Label tipo_user1;
        private Label nome1;
        private Label nome_user1;
        private Label senha1;
        private Label cpf1;
        private Label label9;
        private TextBox email;
        private TextBox nome;
        private TextBox nome_user;
        private TextBox cpf;
        private OpenFileDialog openFileDialog1;
        private TextBox senha;
        private TextBox confsenha;
        private Label label1;
        private Button cad;
        private ComboBox tipo_user;
        private Label lblMensagem;
    }
}