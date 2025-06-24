<?php

namespace App\Controllers;

use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use App\Models\ProductModel;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $client;
    protected $apiKey;
    protected $Transaction;
    protected $Transaction_detail;

    public function __construct()
    {
        helper(['number', 'form']);
        $this->cart = \Config\Services::cart();
        $this->client = new \GuzzleHttp\Client();
        $this->apiKey = env('COST_KEY');
        $this->Transaction = new TransactionModel();
        $this->Transaction_detail = new TransactionDetailModel();
    }

    public function index()
    {
        $data['items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();
        return view('v_keranjang', $data);
    }

    public function cart_add()
    {
        $productModel = new ProductModel();
        $product = $productModel->find($this->request->getPost('id'));

        $this->cart->insert([
            'id'      => $product['id'],
            'qty'     => 1,
            'price'   => $product['harga'],
            'name'    => $product['nama'],
            'weight'  => $product['weight'],
            'options' => ['foto' => $product['foto']]
        ]);

        session()->setFlashdata('success', 'Produk berhasil ditambahkan ke keranjang. (<a href="' . base_url() . 'keranjang">Lihat</a>)');
        return redirect()->to(base_url('/'));
    }

    public function cart_clear()
    {
        $this->cart->destroy();
        session()->setFlashdata('success', 'Keranjang Berhasil Dikosongkan');
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_edit()
    {
        $i = 1;
        foreach ($this->cart->contents() as $value) {
            $this->cart->update([
                'rowid' => $value['rowid'],
                'qty'   => $this->request->getPost('qty' . $i++)
            ]);
        }

        session()->setFlashdata('success', 'Keranjang Berhasil Diedit');
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);
        session()->setFlashdata('success', 'Keranjang Berhasil Dihapus');
        return redirect()->to(base_url('keranjang'));
    }

    public function checkout()
    {
        $items = $this->cart->contents();
        $total_berat = 0;

        foreach ($items as $item) {
            $total_berat += ($item['weight'] ?? 0) * $item['qty'];
        }

        $data['items'] = $items;
        $data['total'] = $this->cart->total();
        $data['total_berat'] = $total_berat;

        return view('v_checkout', $data);
    }

    public function getLocation()
    {
        $search = $this->request->getGet('search');

        $response = $this->client->request('GET', 'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search=' . $search . '&limit=50', [
            'headers' => [
                'accept' => 'application/json',
                'key' => $this->apiKey,
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        return $this->response->setJSON($body['data']);
    }

    public function getCost()
    {
        $destination = $this->request->getGet('destination');
        $weight = $this->request->getGet('weight') ?? 1000;

        $response = $this->client->request('POST', 'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'multipart' => [
                ['name' => 'origin', 'contents' => '64999'],
                ['name' => 'destination', 'contents' => $destination],
                ['name' => 'weight', 'contents' => $weight],
                ['name' => 'courier', 'contents' => 'jne']
            ],
            'headers' => [
                'accept' => 'application/json',
                'key' => $this->apiKey,
            ],
        ]);

        $body = json_decode($response->getBody(), true);
        return $this->response->setJSON($body['data']);
    }

    public function buy()
    {
        if ($this->request->getPost()) {
            $dataForm = [
                'username' => $this->request->getPost('username'),
                'total_harga' => $this->request->getPost('total_harga'),
                'alamat' => $this->request->getPost('alamat'),
                'ongkir' => $this->request->getPost('ongkir'),
                'status' => 0,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ];

            $this->Transaction->insert($dataForm);

            $last_insert_id = $this->Transaction->getInsertID();

            foreach ($this->cart->contents() as $value) {
                $dataFormDetail = [
                    'transaction_id' => $last_insert_id,
                    'product_id' => $value['id'],
                    'jumlah' => $value['qty'],
                    'diskon' => 0,
                    'subtotal_harga' => $value['qty'] * $value['price'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];

                $this->Transaction_detail->insert($dataFormDetail);
            }

            $this->cart->destroy();
            return redirect()->to(base_url());
        }
    }
}
