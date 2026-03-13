namespace Projeto_integrador
{
    partial class Trailer
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
            wb_trailer = new Microsoft.Web.WebView2.WinForms.WebView2();
            btn_nova = new Button();
            ((System.ComponentModel.ISupportInitialize)wb_trailer).BeginInit();
            SuspendLayout();
            // 
            // wb_trailer
            // 
            wb_trailer.AllowExternalDrop = true;
            wb_trailer.CreationProperties = null;
            wb_trailer.DefaultBackgroundColor = Color.White;
            wb_trailer.Location = new Point(-1, 0);
            wb_trailer.Name = "wb_trailer";
            wb_trailer.Size = new Size(800, 448);
            wb_trailer.TabIndex = 0;
            wb_trailer.ZoomFactor = 1D;
            // 
            // btn_nova
            // 
            btn_nova.BackColor = Color.FromArgb(168, 3, 12);
            btn_nova.FlatStyle = FlatStyle.Flat;
            btn_nova.Font = new Font("Century Gothic", 12F, FontStyle.Bold, GraphicsUnit.Point, 0);
            btn_nova.ForeColor = Color.FromArgb(234, 234, 234);
            btn_nova.Location = new Point(320, 461);
            btn_nova.Margin = new Padding(4);
            btn_nova.Name = "btn_nova";
            btn_nova.Size = new Size(156, 62);
            btn_nova.TabIndex = 9;
            btn_nova.Text = "Sortear Novamente";
            btn_nova.UseVisualStyleBackColor = false;
            btn_nova.Click += btn_nova_Click;
            // 
            // Trailer
            // 
            AutoScaleDimensions = new SizeF(7F, 15F);
            AutoScaleMode = AutoScaleMode.Font;
            BackColor = Color.FromArgb(10, 15, 28);
            ClientSize = new Size(800, 536);
            Controls.Add(btn_nova);
            Controls.Add(wb_trailer);
            Name = "Trailer";
            Text = "Trailer";
            Load += Trailer_Load;
            ((System.ComponentModel.ISupportInitialize)wb_trailer).EndInit();
            ResumeLayout(false);
        }

        #endregion

        private Microsoft.Web.WebView2.WinForms.WebView2 wb_trailer;
        private Button btn_nova;
    }
}