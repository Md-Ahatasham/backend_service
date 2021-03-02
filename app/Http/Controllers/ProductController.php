<?php

namespace App\Http\Controllers;
use App\Product;
use Illuminate\Http\Request;
use Image;
class ProductController extends Controller{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $productList = Product::all();
        return response()->json(['productList'=>$productList],200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $this->formValidation($request);
        
        $image_url = '';
        if($request->photo != '')
          {
            //foreach($request->photo as $images){
            $strpos = strpos($request->photo,';');
            $sub = substr($request->photo,0,$strpos);
            $ex = explode('/',$sub)[1];
            $image_extension = time().".".$ex;
            $image = Image::make($request->photo)->resize(200,200);
            // $image_extension = str_random(5).'.'.$images->getClientOriginalExtension();
            // $location = public_path('images/products/'.$img);
            $upload_path = public_path('uploads');
            $image_url = $image_extension;
            $image->save($upload_path.'/'.$image_extension);
        
            //} 
         }

        $product = new Product();
        $product->title = $request->title;
        $product->description = strip_tags($request->description);
        $product->price = $request->price;
        $product->image = $image_url;
        
        $product->save();

        return ['status'=>'success'];

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $productById = Product::find($id);
        return response()->json(['productById'=>$productById],200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product,$id)
    {
        $this->formValidation($request);
        
        $file_url = '';
        $product = Product::find($id);
        if($product->image != $request->image){
            $strpos = strpos($request->image,';');
            $sub = substr($request->image,0,$strpos);
            $ex = explode('/',$sub)[1];
            $image_extension = time().".".$ex;
            $image = Image::make($request->image)->resize(200,200);
            // $image_extension = str_random(5).'.'.$images->getClientOriginalExtension();
            // $location = public_path('images/products/'.$img);
            $upload_path = public_path('uploads');
            $image_url = $image_extension;
            $image->save($upload_path.'/'.$image_extension);
                    
        }else{
                $image_url = $product->image;
             }
        
        $product->title = $request->title;
        $product->description = strip_tags($request->description);
        $product->price = $request->price;
        $product->image = $image_url;
        $product->save();
        return ['status'=>'success'];
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Content  $content
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product,$id)
    {
        $product = Product::find($id);
        $desired_image = public_path('uploads/'.$product->image);
        if(file_exists($desired_image)){
             @unlink($desired_image);
        }

        Product::destroy($id);
        return ['status'=>'success'];
    }


    public function formValidation($request){
        $this->validate($request,[
            'title' => 'required',
            'description' => 'required',
            'price' => 'required',
            
        ]);
    }
}
