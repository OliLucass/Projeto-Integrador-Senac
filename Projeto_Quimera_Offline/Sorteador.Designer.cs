namespace Projeto_integrador
{
    partial class Sorteador
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
            components = new System.ComponentModel.Container();
            System.ComponentModel.ComponentResourceManager resources = new System.ComponentModel.ComponentResourceManager(typeof(Sorteador));
            pictureBox1 = new PictureBox();
            lb_titulo = new Label();
            txt_user = new TextBox();
            btn_bibl = new Button();
            btn_loja = new Button();
            btn_sortear = new Button();
            pt_image_jogo = new PictureBox();
            lb_resposta = new Label();
            btn_nova = new Button();
            grp_resultado = new GroupBox();
            btn_jogar = new Button();
            btn_trailer = new Button();
            timer_an = new System.Windows.Forms.Timer(components);
            cb_cate = new ComboBox();
            lb_cate = new Label();
            ((System.ComponentModel.ISupportInitialize)pictureBox1).BeginInit();
            ((System.ComponentModel.ISupportInitialize)pt_image_jogo).BeginInit();
            grp_resultado.SuspendLayout();
            SuspendLayout();
            // 
            // pictureBox1
            // 
            pictureBox1.Image = (Image)resources.GetObject("pictureBox1.Image");
            pictureBox1.Location = new Point(372, 15);
            pictureBox1.Margin = new Padding(4);
            pictureBox1.Name = "pictureBox1";
            pictureBox1.Size = new Size(294, 163);
            pictureBox1.SizeMode = PictureBoxSizeMode.StretchImage;
            pictureBox1.TabIndex = 0;
            pictureBox1.TabStop = false;
            // 
            // lb_titulo
            // 
            lb_titulo.AutoSize = true;
            lb_titulo.BackColor = Color.FromArgb(168, 3, 12);
            lb_titulo.FlatStyle = FlatStyle.Popup;
            lb_titulo.Font = new Font("SansSerif", 14.2499981F, FontStyle.Italic, GraphicsUnit.Point, 2);
            lb_titulo.ForeColor = Color.FromArgb(234, 234, 234);
            lb_titulo.Location = new Point(193, 204);
            lb_titulo.Margin = new Padding(4, 0, 4, 0);
            lb_titulo.Name = "lb_titulo";
            lb_titulo.Size = new Size(651, 22);
            lb_titulo.TabIndex = 1;
            lb_titulo.Text = "Escolha um Jogo Aleatório da sua Biblioteca Quimera, ou da  Própria Loja ";
            // 
            // txt_user
            // 
            txt_user.BackColor = Color.White;
            txt_user.CharacterCasing = CharacterCasing.Upper;
            txt_user.Font = new Font("Lucida Console", 12F, FontStyle.Regular, GraphicsUnit.Point, 0);
            txt_user.ForeColor = Color.Black;
            txt_user.Location = new Point(193, 391);
            txt_user.Margin = new Padding(4);
            txt_user.Name = "txt_user";
            txt_user.Size = new Size(651, 23);
            txt_user.TabIndex = 2;
            txt_user.Tag = "";
            txt_user.TextChanged += txt_user_TextChanged;
            // 
            // btn_bibl
            // 
            btn_bibl.BackColor = Color.FromArgb(168, 3, 12);
            btn_bibl.FlatStyle = FlatStyle.Flat;
            btn_bibl.Font = new Font("Century Gothic", 12F, FontStyle.Bold, GraphicsUnit.Point, 0);
            btn_bibl.ForeColor = Color.FromArgb(234, 234, 234);
            btn_bibl.Location = new Point(193, 274);
            btn_bibl.Margin = new Padding(4);
            btn_bibl.Name = "btn_bibl";
            btn_bibl.Size = new Size(228, 48);
            btn_bibl.TabIndex = 3;
            btn_bibl.Text = "Minha Biblioteca";
            btn_bibl.UseVisualStyleBackColor = false;
            btn_bibl.Click += btn_bibl_Click_1;
            // 
            // btn_loja
            // 
            btn_loja.BackColor = Color.FromArgb(168, 3, 12);
            btn_loja.FlatStyle = FlatStyle.Flat;
            btn_loja.Font = new Font("Century Gothic", 12F, FontStyle.Bold, GraphicsUnit.Point, 0);
            btn_loja.ForeColor = Color.FromArgb(234, 234, 234);
            btn_loja.Location = new Point(616, 274);
            btn_loja.Margin = new Padding(4);
            btn_loja.Name = "btn_loja";
            btn_loja.Size = new Size(228, 48);
            btn_loja.TabIndex = 4;
            btn_loja.Text = "Toda Loja";
            btn_loja.UseVisualStyleBackColor = false;
            btn_loja.Click += btn_loja_Click;
            // 
            // btn_sortear
            // 
            btn_sortear.BackColor = Color.FromArgb(168, 3, 12);
            btn_sortear.FlatStyle = FlatStyle.Flat;
            btn_sortear.Font = new Font("Century Gothic", 12F, FontStyle.Bold, GraphicsUnit.Point, 0);
            btn_sortear.ForeColor = Color.FromArgb(234, 234, 234);
            btn_sortear.Location = new Point(474, 498);
            btn_sortear.Margin = new Padding(4);
            btn_sortear.Name = "btn_sortear";
            btn_sortear.Size = new Size(96, 48);
            btn_sortear.TabIndex = 5;
            btn_sortear.Text = "Sortear";
            btn_sortear.UseVisualStyleBackColor = false;
            btn_sortear.Click += btn_sortear_Click;
            // 
            // pt_image_jogo
            // 
            pt_image_jogo.Location = new Point(404, 27);
            pt_image_jogo.Margin = new Padding(4);
            pt_image_jogo.Name = "pt_image_jogo";
            pt_image_jogo.Size = new Size(243, 299);
            pt_image_jogo.TabIndex = 6;
            pt_image_jogo.TabStop = false;
            pt_image_jogo.Click += pt_image_jogo_Click;
            // 
            // lb_resposta
            // 
            lb_resposta.Anchor = AnchorStyles.Top | AnchorStyles.Bottom | AnchorStyles.Left | AnchorStyles.Right;
            lb_resposta.BackColor = Color.Transparent;
            lb_resposta.BorderStyle = BorderStyle.Fixed3D;
            lb_resposta.FlatStyle = FlatStyle.Flat;
            lb_resposta.Font = new Font("Century Gothic", 15.75F, FontStyle.Bold | FontStyle.Italic, GraphicsUnit.Point, 0);
            lb_resposta.ForeColor = Color.White;
            lb_resposta.Location = new Point(231, 418);
            lb_resposta.Margin = new Padding(4, 0, 4, 0);
            lb_resposta.Name = "lb_resposta";
            lb_resposta.Size = new Size(586, 108);
            lb_resposta.TabIndex = 7;
            lb_resposta.Text = "Resposta do jogo sorteado";
            lb_resposta.TextAlign = ContentAlignment.MiddleCenter;
            lb_resposta.Click += lb_resposta_Click;
            // 
            // btn_nova
            // 
            btn_nova.BackColor = Color.FromArgb(168, 3, 12);
            btn_nova.FlatStyle = FlatStyle.Flat;
            btn_nova.Font = new Font("Century Gothic", 12F, FontStyle.Bold, GraphicsUnit.Point, 0);
            btn_nova.ForeColor = Color.FromArgb(234, 234, 234);
            btn_nova.Location = new Point(445, 543);
            btn_nova.Margin = new Padding(4);
            btn_nova.Name = "btn_nova";
            btn_nova.Size = new Size(156, 62);
            btn_nova.TabIndex = 8;
            btn_nova.Text = "Sortear Novamente";
            btn_nova.UseVisualStyleBackColor = false;
            btn_nova.Click += btn_nova_Click;
            // 
            // grp_resultado
            // 
            grp_resultado.Controls.Add(btn_jogar);
            grp_resultado.Controls.Add(btn_trailer);
            grp_resultado.Controls.Add(pt_image_jogo);
            grp_resultado.Controls.Add(btn_nova);
            grp_resultado.Controls.Add(lb_resposta);
            grp_resultado.Location = new Point(0, 0);
            grp_resultado.Margin = new Padding(4);
            grp_resultado.Name = "grp_resultado";
            grp_resultado.Padding = new Padding(4);
            grp_resultado.Size = new Size(1048, 617);
            grp_resultado.TabIndex = 9;
            grp_resultado.TabStop = false;
            grp_resultado.Text = "groupBox1";
            grp_resultado.Visible = false;
            // 
            // btn_jogar
            // 
            btn_jogar.Location = new Point(488, 333);
            btn_jogar.Name = "btn_jogar";
            btn_jogar.Size = new Size(75, 23);
            btn_jogar.TabIndex = 10;
            btn_jogar.Text = "Jogar?";
            btn_jogar.UseVisualStyleBackColor = true;
            btn_jogar.Visible = false;
            btn_jogar.Click += btn_jogar_Click;
            // 
            // btn_trailer
            // 
            btn_trailer.BackColor = Color.FromArgb(168, 3, 12);
            btn_trailer.FlatStyle = FlatStyle.Flat;
            btn_trailer.ForeColor = Color.FromArgb(234, 234, 234);
            btn_trailer.Location = new Point(445, 365);
            btn_trailer.Name = "btn_trailer";
            btn_trailer.Size = new Size(156, 38);
            btn_trailer.TabIndex = 9;
            btn_trailer.Text = "Veja o trailer aqui!";
            btn_trailer.UseVisualStyleBackColor = false;
            btn_trailer.Click += btn_trailer_Click;
            // 
            // timer_an
            // 
            timer_an.Interval = 50;
            // 
            // cb_cate
            // 
            cb_cate.FormattingEnabled = true;
            cb_cate.Location = new Point(463, 295);
            cb_cate.Name = "cb_cate";
            cb_cate.Size = new Size(123, 27);
            cb_cate.TabIndex = 10;
            // 
            // lb_cate
            // 
            lb_cate.AutoSize = true;
            lb_cate.BackColor = Color.FromArgb(168, 3, 12);
            lb_cate.BorderStyle = BorderStyle.Fixed3D;
            lb_cate.Font = new Font("Century Gothic", 12F, FontStyle.Bold, GraphicsUnit.Point, 0);
            lb_cate.ForeColor = Color.FromArgb(234, 234, 234);
            lb_cate.Location = new Point(477, 271);
            lb_cate.Name = "lb_cate";
            lb_cate.Size = new Size(95, 21);
            lb_cate.TabIndex = 11;
            lb_cate.Text = "Categorias";
            // 
            // Sorteador
            // 
            AutoScaleDimensions = new SizeF(9F, 19F);
            AutoScaleMode = AutoScaleMode.Font;
            BackColor = Color.FromArgb(10, 15, 28);
            ClientSize = new Size(1048, 618);
            Controls.Add(lb_cate);
            Controls.Add(cb_cate);
            Controls.Add(grp_resultado);
            Controls.Add(btn_sortear);
            Controls.Add(btn_loja);
            Controls.Add(btn_bibl);
            Controls.Add(txt_user);
            Controls.Add(lb_titulo);
            Controls.Add(pictureBox1);
            Font = new Font("SansSerif", 12F, FontStyle.Regular, GraphicsUnit.Point, 2);
            Icon = (Icon)resources.GetObject("$this.Icon");
            Margin = new Padding(4);
            Name = "Sorteador";
            StartPosition = FormStartPosition.CenterScreen;
            Text = "Sorteador";
            Load += Sorteador_Load;
            ((System.ComponentModel.ISupportInitialize)pictureBox1).EndInit();
            ((System.ComponentModel.ISupportInitialize)pt_image_jogo).EndInit();
            grp_resultado.ResumeLayout(false);
            ResumeLayout(false);
            PerformLayout();
        }

        #endregion

        private PictureBox pictureBox1;
        private Label lb_titulo;
        private TextBox txt_user;
        private Button btn_bibl;
        private Button btn_loja;
        private Button btn_sortear;
        private PictureBox pt_image_jogo;
        private Label lb_resposta;
        private Button btn_nova;
        private GroupBox grp_resultado;
        private System.Windows.Forms.Timer timer_an;
        private ComboBox cb_cate;
        private Label lb_cate;
        private Button btn_trailer;
        private Button btn_jogar;
    }
}