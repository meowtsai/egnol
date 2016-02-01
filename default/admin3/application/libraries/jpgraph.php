<?php 

require_once 'jpgraph/src/jpgraph.php';  
require_once 'jpgraph/src/jpgraph_bar.php';   
require_once 'jpgraph/src/jpgraph_pie.php';   
  
class Jpgraph
{  
	public function bar_chart($data, $labels, $save_file="", $settings="") {

		if ($save_file && file_exists($save_file)) unlink($save_file);
		if ($data == array()) return 0;		
		if ($labels == array()) return 0;		
		
		// Create the graph. These two calls are always required
		$graph = new Graph(720,300,'auto');
		$graph->SetScale("textlin");

		$theme_class=new UniversalTheme;
		$graph->SetTheme($theme_class);

		$graph->Set90AndMargin(50,40,40,40);
		$graph->img->SetAngle(90); 

		// set major and minor tick positions manually
		$graph->SetBox(false);

		//$graph->ygrid->SetColor('gray');
		$graph->ygrid->Show(false);
		$graph->ygrid->SetFill(false);
		$graph->xaxis->SetTickLabels($labels);
		$graph->yaxis->HideLine(false);
		$graph->yaxis->HideTicks(false,false);

		// For background to be gradient, setfill is needed first.
		$graph->SetBackgroundGradient('#EEEEEE', '#EEEEEE', GRAD_HOR, BGRAD_PLOT);

		// Create the bar plots
		$b1plot = new BarPlot($data);

		// ...and add it to the graPH
		$graph->Add($b1plot);

		$b1plot->SetWeight(0);
		//$b1plot->SetFillGradient("#808000","#90EE90",GRAD_HOR);
		$b1plot->SetWidth(17);

		// Display the graph
		return $graph->Stroke($save_file);
	}
    
	public function pie_chart($data, $labels, $title="", $save_file="", $slice_color=array(), $settings="") {

		if ($save_file && file_exists($save_file)) unlink($save_file);
		if ($data == array()) return 0;	
		if ($labels == array()) return 0;		

        // Create the Pie Graph. 
        $graph = new PieGraph(350,250);

        $theme_class="DefaultTheme";
        //$graph->SetTheme(new $theme_class());

        // Set A title for the plot
        $graph->title->Set($title);
        $graph->SetBox(true);

        // Create
        $p1 = new PiePlot($data);
        $graph->Add($p1);

        $p1->SetLabels($labels);
        $p1->ShowBorder();
        $p1->SetColor('black');
        $p1->SetSliceColors($slice_color);
        return $graph->Stroke($save_file);
	}
}