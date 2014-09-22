<?php
namespace Axado;

use TestCase;
use Mockery as m;

class ResponseTest extends TestCase
{

    public function tearDown()
    {
        parent::tearDown();
        m::close();
    }

    public function testShouldReturnPropertlyIsOk()
    {
        $response = new Response;

        $this->assertFalse($response->isOk());
    }

    public function testShouldParse()
    {
        // Set
        $response = m::mock('Axado\Response[parseQuotations,isError]');

        // Expect
        $response->shouldReceive('isError')
            ->once()
            ->andReturn(false);

        $response->shouldReceive('parseQuotations')
            ->once()
            ->with([]);

        // Assert
        $response->parse("{ raw: '12.0' }");
        $this->assertTrue($response->isOk());
    }

    public function testShouldParseNotIfHasError()
    {
        // Set
        $response = m::mock('Axado\Response[parseQuotations,isError]');

        // Expect
        $response->shouldReceive('isError')
            ->once()
            ->andReturn(true);

        $response->shouldReceive('parseQuotations')
            ->never();

        // Assert
        $response->parse("{ raw }");
        $this->assertFalse($response->isOk());
    }

    public function testShouldIfNotErrorReturnFalse()
    {
        // Set
        $response = new Response;
        $data = ["right object" => true];

        // Expect
        $result = $response->isError($data);

        // Assert
        $this->assertFalse($result);
    }
}