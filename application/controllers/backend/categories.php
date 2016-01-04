<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Categories extends CI_Controller {

	public function __construct()
    {
        parent::__construct();   
        $this->load->model('Categories_model','datamodel');  
		$this->load->helper(array('form', 'url'));
		$this->load->library('image_lib');	
		$this->load->library('upload');			
    }
	   
	public function index()
	{
		$data['title']='List Of Categories';	
		$data['array_categories'] = $this->datamodel->get_categories();
		$this->mytemplate->loadBackend('categories',$data);
	}

	public function form($mode,$id='')
	{
		$data['title']=($mode=='insert')? 'Add Categories' : 'Update Categories';				
		$data['categories'] = ($mode=='update') ? $this->datamodel->get_categories_by_id($id) : '';
		$this->mytemplate->loadBackend('frmcategories',$data);	
	}

	public function process($mode,$id='')
	{
		
		if(($mode=='insert') || ($mode=='update'))
		{
			$this->do_upload();
			$result = ($mode=='insert') ? $this->datamodel->insert_entry($this->upload->file_name) : $this->datamodel->update_entry($this->upload->file_name);
		}else if($mode=='delete'){
			$result = $this->datamodel->hapus($id);	
		}	
		if ($result) redirect(site_url('backend/categories'),'location');
	}
	
	private function dependensi($id)
	{
		return $this->datamodel->cek_dependensi($id);
	}
	
	
	public function do_upload()
        {
                $config['upload_path']          = './uploads/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 100;
                $config['max_width']            = 1024;
                $config['max_height']           = 768;
				$config['encrypt_name'] 		= TRUE;

                $this->upload->initialize($config);
				$this->load->library('upload', $config);

                if ( ! $this->upload->do_upload())
                {
                        $error = array('error' => $this->upload->display_errors());

                        echo "gagal";
                }
                else
                {
                       echo "berhasil";
					   $this->resize_image();
					   $this->wmark();
					   $this->thumbnail();
                }
        }

	private function resize_image()
	{
			$config1['image_library'] = 'gd2';
			$config1['source_image'] = './uploads/'.$this->upload->file_name;
			$config1['maintain_ratio'] = TRUE;
			$config1['width']         = 600;
			$config1['height']       = 400;
			$config['create_thumb'] = TRUE;
			
			$this->image_lib->initialize($config1);	
			$this->load->library('image_lib', $config1);
			
			if ( ! $this->image_lib->resize())
			{
				echo $this->image_lib->display_errors();
			}

			$this->image_lib->clear();
	}
	
	private function wmark()
	{

			$config['image_library'] = 'gd2';
			$config['source_image'] = './uploads/'.$this->upload->file_name;
			$config['wm_text'] = '12131257';
			$config['wm_type'] = 'text';
			$config['wm_font_path'] = './system/fonts/texb.ttf';
			$config['wm_font_size'] = 35;
			$config['wm_font_color'] = 'ffffff';
			$config['wm_vrt_alignment'] = 'middle';
			$config['wm_hor_alignment'] = 'center';
			$config['wm_padding'] = 20;
			$config['overwrite'] = TRUE;
			
			
			$this->image_lib->initialize($config);	
			$this->load->library('image_lib',$config);
		
			
			if ( ! $this->image_lib->watermark())
			{
				echo $this->image_lib->display_errors();
			}
			$this->image_lib->clear();
	}
	
	private function thumbnail()
	{
			$config['image_library'] = 'gd2';
			$config['source_image'] = './uploads/'.$this->upload->file_name;
			$config['new_image'] = './uploads/thumbs'; //mengcopy image ke folder thumbs
			$config['create_thumb'] = TRUE;
			$config['thumb_marker'] = '_thumb';
			$config['maintain_ratio'] = TRUE;
			$config['width']         = 75;
			$config['height']       = 50;
			
			$this->image_lib->initialize($config);	
			$this->load->library('image_lib', $config);
			
			if ( ! $this->image_lib->resize())
			{
				echo $this->image_lib->display_errors();
			}
	
			$this->image_lib->clear();
	}
	
	
}



/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */

